<?php

/**
 * @文件： countdown_model
 * @时间： 2015-6-10 10:48:32
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：倒计时
 */
class countdown_model extends CI_Model {

    private $CountDownKeyPre = 'CountDown_';
    private $CountDownKey;

    function __construct() {
        parent::__construct();
    }

    function listData($whereData, $sort = 'update_time', $order = 'desc', $offset = 0, $per_page = 10, $fields = '*') {
        $this->db->select($fields);
        $this->db->from('countdown');
        $this->db->where($whereData);
        if($per_page != 'ALL'){
            $this->db->limit($per_page, $offset);
        }
        $this->db->order_by($sort, $order);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count($whereData) {
        $this->db->where($whereData);
        $this->db->from('countdown');
        return $this->db->count_all_results();
    }

    //根据ID查找倒计时信息
    //正确返回array,错误返回 false
    function getInfoById($countdown_id) {
        $this->CountDownKey = $this->CountDownKeyPre . $countdown_id;
        if ($this->redis->exists($this->CountDownKey)) {
            $row = $this->redis->hashGet($this->CountDownKey, NULL, 2);
        } else {
            $fields = 'id,name,start,end,cycle,auto_recount,price,rate,decimal,creator,create_time,update_time,status';
            $this->db->select($fields);
            $this->db->where('id', $countdown_id);
            $this->db->limit(1);
            $query = $this->db->get('countdown');
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $this->redis->hashSet($this->CountDownKey, $row);
                if ($row['auto_recount'] == 2) {
                    $row['end'] > time() ? $this->redis->timeOut($this->CountDownKey, $row['end'] - time() + 100) : $this->redis->timeOut($this->CountDownKey, 600);
                }
            } else {
                $row = false;
            }
        }
        return $row;
    }

    //添加倒计时
    function insert($data) {
        if ($this->db->insert('countdown', $data)) {
            $countdown_id = $this->db->insert_id();
            $this->CountDownKey = $this->CountDownKeyPre . $countdown_id;
            $data['id'] = $countdown_id;
            $this->redis->hashSet($this->CountDownKey, $data);
            $data['auto_recount'] == 2 ? $this->redis->timeOut($this->CountDownKey, $data['end'] - time() + 100) : $this->redis->persist($this->CountDownKey);
            return $data['id'];
        } else {
            return 0;
        }
    }

    //修改倒计时
    function update($countdown_id, $data) {
        $this->db->where('id', $countdown_id);
        if ($this->db->update('countdown', $data)) {
            $this->CountDownKey = $this->CountDownKeyPre . $countdown_id;
            if ($this->redis->exists($this->CountDownKey)) {
                $this->redis->hashSet($this->CountDownKey, $data);
            }
            return 1;
        } else {
            return 0;
        }
    }

    function changeStatus($whereData, $updateData) {
        $this->db->where($whereData);
        if ($this->db->update('countdown', $updateData)) {
            $this->CountDownKey = $this->CountDownKeyPre . $whereData['id'];
            if ($this->redis->exists($this->CountDownKey)) {
                $this->redis->hashSet($this->CountDownKey, $updateData);
            }
            return TRUE;
        } else {
            return false;
        }
    }

    function delete($whereData) {
        $this->db->where($whereData);
        return $this->db->delete('countdown');
    }

    //添加一个产品到countdown
    //$product_id：产品ID
    //$country_codes：国家代码串：AU,US
    //$countdown_id：倒计时ID
    function addOneProduct($product_id, $country_codes, $countdown_id) {
        $countryCodeArr = explode(',', $country_codes);
        if (is_array($product_id)) {
            foreach ($product_id as $key => $vo) {
                if (!is_object($vo)) {
                    $product_id[$key] = new MongoId($vo);
                }
            }
            $removeWhere = array();
            $removeParam = array(
                '$pullAll' => array(
                    'product' => $product_id
                )
            );
            $updateWhere = array('_id' => new MongoInt32($countdown_id));
            $updateParam = array(
                '$pushAll' => array(
                    'product' => $product_id
                )
            );
        } else {
            if (!is_object($product_id)) {
                $product_id = new MongoId($product_id);
            }
            $removeWhere = array();
            $removeParam = array(
                '$pull' => array(
                    'product' => $product_id
                )
            );
            $updateWhere = array('_id' => new MongoInt32($countdown_id));
            $updateParam = array(
                '$push' => array(
                    'product' => $product_id
                )
            );
        }
        
        foreach ($countryCodeArr as $country_code) {
            $collection = $this->mongo->{$country_code . '_countdown'};
            $collection->update($removeWhere, $removeParam, array('multiple' => true));
            $collection->update($updateWhere, $updateParam, array('upsert' => true));
            //$insert_data = array('table_name'=>$country_code . '_countdown','command'=>2,'data'=>json_encode($removeParam),'condition'=>json_encode($removeWhere),'multiple'=>1);
            //$this->db->insert('mongodb_queue',$insert_data);
            //$insert_data = array('table_name'=>$country_code . '_countdown','command'=>2,'data'=>json_encode($updateParam),'condition'=>json_encode($updateWhere),'multiple'=>1);
            //$this->db->insert('mongodb_queue',$insert_data);
        }
        return true;
    }

    function clearOneProduct($product_id, $country_codes) {
        $countryCodeArr = explode(',', $country_codes);
        $removeWhere = array();
        $removeParam = array(
            '$pull' => array(
                'product' => new MongoId($product_id)
            )
        );
        foreach ($countryCodeArr as $country_code) {
            $collection = $this->mongo->{$country_code . '_countdown'};
            $collection->update($removeWhere, $removeParam, array('multiple' => true));
            //$insert_data = array('table_name'=>$country_code . '_countdown','command'=>2,'data'=>json_encode($removeParam),'condition'=>json_encode($removeWhere),'multiple'=>1);
            //$this->db->insert('mongodb_queue',$insert_data);
        }
        return true;
    }

    //根据产品ID获得倒时计ID
    function getInfoByProductId($country_code, $product_id) {
        $collection = $this->mongo->{$country_code . '_countdown'};
        $where = array(
            'product' => new MongoId($product_id)
        );
        return $collection->findOne($where, array('_id' => 1));
    }

    /*     * ************************************************* */
    /*     * **************前端调用**************************** */
    /*     * ************************************************* */

    //计算倒计时价
    //$price商品价格
    function getPrice($countdown_id, $price) {
        $countDownFields = $this->getInfoById($countdown_id);
        $resultPrice = $price;
        if (is_array($countDownFields)) {
            if ($countDownFields['status'] == 2) {//倒计时是否开启
                $time = time();
                if ($countDownFields['start'] < $time) {//倒计时是否开始
                    if ($countDownFields['auto_recount'] == 1) {//倒计时是否自动续期
                        $resultPrice = $this->calculatePrice($price, $countDownFields['price'], $countDownFields['rage'], $countDownFields['decimal']);
                    } else {
                        if ($countDownFields['end'] > $time) {//倒计时在有效周期内
                            $resultPrice = $this->calculatePrice($price, $countDownFields['price'], $countDownFields['rage'], $countDownFields['decimal']);
                        }
                    }
                }
            }
        }
        return $resultPrice;
    }

    private function calculatePrice($price, $lessPrice, $lessScale, $lastNumber) {
        $result = round(($price - $lessPrice) * (1 - $lessScale / 100));
        if ($lastNumber > -1) {
            $result = floor($result / 100) * 100 + $lastNumber;
        }
        return $result;
    }

    //前端调用
    //计算倒计时距离结束的时间
    //$start：倒计时起始时间
    //$cycle：轮转周期
    function getEndTime($start, $cycle) {
        $hasBeenExecuted = time() - $start; //倒计时总共执行时长
        $hasBeenExecutedTime = $hasBeenExecuted % $cycle; //最后一轮已执行时长
        $laveTime = $cycle - $hasBeenExecutedTime; //最后一轮剩余时长
        $result['day'] = floor($laveTime / 86400);
        $result['hour'] = floor($laveTime % 86400 / 3600);
        $result['minute'] = floor($laveTime % 86400 % 3600 / 60);
        $result['second'] = $laveTime % 86400 % 3600 % 60;
        return $result;
    }

}
