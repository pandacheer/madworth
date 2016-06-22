<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class home extends MY_Controller {

    private $member_email;
    private $member_id;
    private $terminal;

    function __construct() {
        parent::__construct();
        $this->page['sessionID'] = $this->session->userdata('session_id');
        $this->page['title'] = 'Happy Grabbing';
        $this->terminal = $this->session->userdata('isMobile');
        $this->load->model('template_model');
        $headView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'head');
        $this->page['head'] = $this->load->view($headView, $this->page, true);

        $footLogosView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot_logos');
        $this->page['footLogosView'] = $this->load->view($footLogosView, $this->page, true);

        $footView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'foot');
        $this->page['foot'] = $this->load->view($footView, $this->page, true);
        $shoppingcartView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'shoppingcart');
        $this->page['shoppingcart'] = $this->load->view($shoppingcartView, $this->page, true);
        $this->load->helper('cookie');
    }

    //shopify老用户激活提示页
    function goActivate($email) {
        $this->page['email'] = $email;
        $accountwelcomeView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'account-welcome');
        $this->load->view($accountwelcomeView, $this->page);
    }

    public function index() {

        $this->load->helper('form');
        $collection = $this->mongo->{$this->page['country'] . '_collection'};
        $this->page['newDeals'] = $collection->findOne(array('seo_url' => 'New-Deals'));
        $this->page['newDeals']['collection_url'] = 'New-Deals';
//        $this->page['newDeals']['sort'] = 'manual';
        $this->page['newDeals'] = $this->getPageProduct($this->page['country'], $this->page['newDeals'], 24);
//        echo '<pre>';
//        print_r($this->page['newDeals']);
//        exit;
        $this->load->model('countdown_model');
        foreach ($this->page['newDeals']['allow'] as $product_id => $productInfo) {
            unset($this->page['newDeals']['allow'][$product_id]['_id']);
            $countdown_id = $this->countdown_model->getInfoByProductId($this->page['country'], $product_id);
            if ($countdown_id) {
                $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
                $time = time();
                if (is_array($countdownInfo) && $countdownInfo['status'] == 2 && $countdownInfo['start'] < $time) {
                    $this->page['newDeals']['allow'][$product_id]['price'] = $this->countdown_model->getPrice($countdown_id, $productInfo['price']);
                    if ($countdownInfo['auto_recount'] == 2) {
                        $this->page['newDeals']['allow'][$product_id]['endTime'] = $this->countdown_model->getEndTime($countdownInfo['start'], $countdownInfo['cycle']);
                    } else {
                        $this->page['newDeals']['allow'][$product_id]['endTime'] = $countdownInfo['end'] >= time() ? $countdownInfo['end'] * 1000 : '';
                    }
                }
            }
        }

        if ($this->page['country'] == 'AU') {
            $this->page['countrySEO'] = '<script type="text/javascript" src="//static.criteo.net/js/ld/ld.js" async="true"></script>
                                             <script type="text/javascript">
                                             window.criteo_q = window.criteo_q || [];
                                             window.criteo_q.push(
                                             { event: "setAccount", account: 22926 },
                                             { event: "setEmail", email: "' . $this->session->userdata('member_email') . '" },
                                             { event: "setSiteType", type: "d" },
                                             { event: "viewHome" }
                                             );
                                             </script>';
        }

        /* slide切换 */
        $this->load->model('slideshow_model');
        $this->page['image'] = $this->slideshow_model->select($this->page['country']);
        $indexView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'index');
        $this->load->view($indexView, $this->page);
    }

    function getPageProduct($coturny_code, $doc, $prePage) {
        $getFields = array('seo_url' => true, 'title' => true, 'sku' => true, 'price' => true, 'sold' => true, 'original' => TRUE, 'image' => TRUE, 'children' => TRUE, 'bundletype' => true, 'freebies' => TRUE,'diy'=>TRUE); //需要查找的字段

        $productMongo = $this->mongo->{$coturny_code . '_product'};
        if ($doc['model'] == 1) {//手动模式
//            $doc['sortProductID'] = array_chunk($doc['allow'], $prePage); //先在数组里切分分页
//            $mongoCondtion = array(
//                '_id' => array('$in' => $doc['sortProductID'][0]),
//                'status'=>1
//            );
            $mongoCondtion = array(
                '_id' => array('$in' => $doc['allow']),
                'status' => 1
            );
//            $doc['sortProductID'] = $mongoCondtion['_id']['$in'];
//            $rows = $productMongo->find($mongoCondtion)->count(); //产品总数
            if ($doc['sort'] == 'manual') {
                $productInfos = $productMongo->find($mongoCondtion, $getFields)->limit($prePage);
                $doc['allow'] = iterator_to_array($productInfos);
            } else {
                $sortArr = explode(',', $doc['sort']);
                $sort = array($sortArr[0] => (int) $sortArr[1]);
                $productInfos = $productMongo->find($mongoCondtion, $getFields)->sort($sort)->limit($prePage);
                $doc['allow'] = iterator_to_array($productInfos);
            }
            $doc['sortProductID'] = array_unique(array_keys($doc['allow']));
        } else {//条件模式
            //拼接条件
            if (count($doc['conditions']) == 1) {
                $mongoCondtion = array($doc['conditions'][0]['fields'] => $doc['conditions'][0]['values']);
            } else {
                $mongoCondtion = [];
                foreach ($doc['conditions'] as $condition) {
                    if ($condition['fields'] == 'type') {
                        $mongoCondtion[] = array($condition['fields'] => (int) $condition['values']);
                    } else {
                        if ($condition['link'] == 'contains') {
                            $mongoCondtion[] = array($condition['fields'] => new MongoRegex("/{$condition['values']}/"));
                        } else {
                            $mongoCondtion[] = array($condition['fields'] => $condition['values']);
                        }
                    }
                }
                if ($doc['relation'] == 'or') {
                    $mongoCondtion = array('$or' => $mongoCondtion);
                }
            }
//            $rows = $productMongo->find($mongoCondtion)->count(); //产品总数
            //手动排序，查找白名单里的商品信息
            if ($doc['sort'] == 'manual') {
                $mongoCondtionAllow = array(
                    '_id' => array('$in' => $doc['allow']),
                    'status' => 1
                );
                $allowProduct = iterator_to_array($productMongo->find($mongoCondtionAllow, $getFields));
                $searchProduct = iterator_to_array($productMongo->find($mongoCondtion, $getFields));
                $same = array_intersect_key($allowProduct, $searchProduct); //交集
                $diff = array_diff_key($searchProduct, $same); //差集
                $doc['allow'] = array_slice(array_merge($same, $diff), 0, $prePage);
            } else {
                $sortArr = explode(',', $doc['sort']);
                $sort = array($sortArr[0] => (int) $sortArr[1]);
                $searchProduct = $productMongo->find($mongoCondtion, $getFields)->sort($sort)->limit($prePage);
                $doc['allow'] = iterator_to_array($searchProduct);
            }
        }
//        $doc['rows'] = $rows;
        unset($doc['model']);
        unset($doc['relation']);
        unset($doc['conditions']);
        unset($doc['status']);
        unset($doc['creator']);
        unset($doc['create_time']);
        unset($doc['disallow']);
        //echo '<pre>';
        //print_r($doc);exit;
        return $doc;
    }

    function showError($errorCode, $msg = '') {
        $a = substr($errorCode, 0, 1);
        if ($a == 'P') {
            $this->page['actionUrl'] = '/cart';
            $this->page['actionTitle'] = 'Try Again';
        } else {
            $this->page['actionUrl'] = '/';
            $this->page['actionTitle'] = 'Continue Shopping';
        }

        if ($msg != '') {
            $this->page['errorMessage'] = urldecode($msg);
        } else {
            $this->load->helper('language');
            if ($a == 'P') {
                $this->lang->load('sys_pay');
            } else {
                $this->lang->load('sys_error');
            }
            $this->page['errorMessage'] = lang($errorCode);
        }

        $showErrorView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError');
        $this->load->view($showErrorView, $this->page);
    }

    function showSuccess($successCode) {
        $this->load->helper('language');
        $this->lang->load('sys_success');
        $this->page['successMessage'] = lang($successCode);
        $this->page['jumpUrl'] = $this->input->get('jumpUrl');
        if ($successCode == "S2002") {
            $showSuccessRegView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showSuccessReg');
            $this->load->view($showSuccessRegView, $this->page);
        } else {
            $showSuccessView = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showSuccess');
            $this->load->view($showSuccessView, $this->page);
        }
    }

    function showError404() {
        header('HTTP/1.0 404 Not Found');
        $showError404View = $this->template_model->getStyle($this->terminal, $this->page['country'], 'showError404');
        $this->load->view($showError404View, $this->page);
    }

    function ttt() {
//$this->load->dbforge();
//$fields = array(
//                        'status' => array('type' => 'int')
//);
//$this->dbforge->add_column('SYS_domain', $fields);
//exit;
//        $fields = array(
//            'domain' => array(
//                'type' => 'VARCHAR',
//                'constraint' => '100',
//            ),
//                        'country' => array(
//                'type' => 'CHAR',
//                'constraint' => '2',
//            ),
//        );
//        $this->dbforge->add_field($fields); 
//        $this->dbforge->create_table('SYS_domain');
//exit;

        $date = new DateTime(date('Y-m-d H:i:s'));
        echo $date->format('Y-m-d H:i:sP') . "\n";
        echo '<br>';
// Specified date/time in the specified time zone.
        $date = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('Pacific/Nauru'));
        echo $date->format('Y-m-d H:i:sP') . "\n";
        exit;


//        date_default_timezone_set("Asia/Shanghai");
        date_default_timezone_set("PRC");
        echo "PRC--" . time() . '----------' . date('Y-m-d H:i:s');
        echo '<br>';
        date_default_timezone_set("UTC");
        echo "UTC--" . time() . '----------' . date('Y-m-d H:i:s');
        echo '<br>';

        date_default_timezone_set("US/Hawaii");
//        date_default_timezone_set("America/New_York");
        echo "USA--" . time() . '----------' . date('Y-m-d H:i:s');
        echo '<br>';

        echo '-----------------------------<br>';


        date_default_timezone_set("US/Hawaii");

        $dateTimePRC = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone("Asia/Shanghai"));
        echo "PRC--" . time() . '-----' . $dateTimePRC->format('Y-m-d H:i:s');
        echo '<br>';

        $dateTimePRC = new DateTime('@' . (time() + 28800), new DateTimeZone("Asia/Shanghai"));
        echo "PRC--" . time() . '-----' . $dateTimePRC->format('Y-m-d H:i:s');
        echo '<br>';

        $dateTimeUTC = new DateTime('@' . time(), new DateTimeZone("UTC"));
        echo "UTC--" . time() . '-----' . $dateTimeUTC->format('Y-m-d H:i:s');
        echo '<br>';

        $dateTimeUSA = new DateTime('@' . time(), new DateTimeZone("US/Hawaii"));
        echo "USA--" . time() . '-----' . $dateTimeUSA->format('Y-m-d H:i:s');
        echo '<br>';
        $OrderNumber = $dateTimePRC->format('y') . str_pad($dateTimePRC->format('W'), 2, '0', STR_PAD_LEFT) . $dateTimePRC->format('N') . str_pad(strtotime($dateTimePRC->format('Y-m-d H:i:s')) - strtotime($dateTimePRC->format('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad(2, 3, '0', STR_PAD_LEFT);
        echo $OrderNumber;

        echo "<br>";
        echo "<br>";
        $OrderNumber = $dateTimePRC->format('y') . str_pad($dateTimePRC->format('W'), 2, '0', STR_PAD_LEFT) . $dateTimePRC->format('N') . str_pad(time() - strtotime($dateTimePRC->format('Y-m-d')), 5, '0', STR_PAD_LEFT) . str_pad(2, 3, '0', STR_PAD_LEFT);
        echo $OrderNumber;
    }

}
