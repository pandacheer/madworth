<?php

class Tag_model extends CI_Model {

    private $CI;
    private $tag;
    private $_tag = '_tag';

    public function __construct() {
        $this->CI = & get_instance();
    }

    public function table($country) {
        $this->tag = $country . $this->_tag;
        $this->tag = $this->CI->mongo->selectCollection($this->tag);
    }

    // 产品添加页面传入的二维数组
    public function addTag($country, $tag) {
        $this->table($country);
        $data = array('$inc' => array('amount' => 1));
        $option = array('upsert' => true);
        $i = 1;
        foreach ($tag as $vo) {
            if (is_array($vo)) {
                foreach ($vo as $vi) {
                    if ($vi != NULL) {
                        $where = array('type' => $i, 'name' => $vi);
                        $this->tag->update($where, $data, $option);
                        //$insert_data = array('table_name'=>  $this->tag,'command'=>3,'data'=>  json_encode($data),'condition'=>  json_encode($where));
                        //$this->db->insert('mongodb_queue',$insert_data);
                    }
                }
            } elseif ($vo) {
                $where = array('type' => $i, 'name' => $vo);
                $this->tag->update($where, $data, $option);
                //$insert_data = array('table_name'=>  $this->tag,'command'=>3,'data'=>  json_encode($data),'condition'=>  json_encode($where));
                //$this->db->insert('mongodb_queue',$insert_data);
            }
            $i++;
        }
    }

    // 处理一维数组$tag
    public function upTag($country, $old, $new) {
        $this->table($country);
        $option = array('upsert' => true);
        foreach ($old as $key => $vo) {
            switch ($key) {
                case 'Tag1':$k = 1;
                    break;
                case 'Tag2':$k = 2;
                    break;
                case 'Tag3':$k = 3;
                    break;
            }
            if (is_array($vo)) {
                foreach ($vo as $vi) {
                    if ($vi != NULL) {
                        $this->tag->update(array('name'=>$vi,'type'=>$k),array('$inc' => array('amount' => -1)));
                        //$insert_data = array('table_name' => $this->tag, 'command' => 3, 'data' => json_encode(array('$inc' => array('amount' => -1))), 'condition' => json_encode(array('name' => $vi, 'type' => $k)));
                        //$this->db->insert('mongodb_queue', $insert_data);
                    }
                }
            } else if ($vo) {
                $this->tag->update(array('name'=>$vo,'type'=>$k),array('$inc' => array('amount' => -1)));
                //$insert_data = array('table_name' => $this->tag, 'command' => 3, 'data' => json_encode(array('$inc' => array('amount' => -1))), 'condition' => json_encode(array('name' => $vo, 'type' => $k)));
                //$this->db->insert('mongodb_queue', $insert_data);
            }
        }
        foreach ($new as $key => $vo) {
            switch ($key) {
                case 'Tag1':$k = 1;
                    break;
                case 'Tag2':$k = 2;
                    break;
                case 'Tag3':$k = 3;
                    break;
            }
            if (is_array($vo)) {
                foreach ($vo as $vi) {
                    if ($vi != NULL) {
                        $this->tag->update(array('name'=>$vi,'type'=>$k),array('$inc' => array('amount' => 1)),$option);
                        //$insert_data = array('table_name' => $this->tag, 'command' => 3, 'data' => json_encode(array('$inc' => array('amount' => 1))), 'condition' => json_encode(array('name' => $vi, 'type' => $k)));
                        //$this->db->insert('mongodb_queue', $insert_data);
                    }
                }
            } elseif ($vo) {
                $this->tag->update(array('name'=>$vo,'type'=>$k),array('$inc' => array('amount' => 1)),$option);
                //$insert_data = array('table_name' => $this->tag, 'command' => 3, 'data' => json_encode(array('$inc' => array('amount' => 1))), 'condition' => json_encode(array('name' => $vo, 'type' => $k)));
                //$this->db->insert('mongodb_queue', $insert_data);
            }
        }
    }

}
