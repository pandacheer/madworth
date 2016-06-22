<?php

class productapi extends PC_Controller {

    protected $country;
    protected $key = '5a6216eadf83591b79422d681e79505a6c5c32a3cfa8058c9b080cb56a0f9a15c1ba4e74059c397bf3e080d7acfc1852';
    protected $signature;
    protected $error = array();
    protected $field_map = array();
    protected $returnfield_map = array();
    private $per = 10; // 分页数量

    private function rewrite_post($field = '', $recorde = true) {
        $posts = file_get_contents("php://input");
        $post = json_decode($posts, true);
        if ($recorde) {
            $this->load->model('productapi_model');
            $r = $post;
            $r['otherinfomation'] = array('method' => $this->uri->segment(2));
            $this->productapi_model->stopostdata($r);
        }
        if (empty($field)) {
            return $post;
        }
        return isset($post[$field]) ? $post[$field] : '';
    }

    public function __construct() {
        parent::__construct();
        ini_set('display_errors', 'Off');
        error_reporting(0);
        $this->error = array(
            'signatureError' => '签名错误(-1)',
            'titleEmpty' => '标题不能为空(-2)',
            'skuEmpty' => 'sku不能为空(-3)',
            'costEmpty' => '成本价格不能为空(-4)',
            'typeEmpty' => '产品类型不能为空(-5)',
            'skuorseourlExists' => 'sku或seo_url已经存在(-6)',
            'countryError' => '国家代号错误(-7)',
            'methodError' => '调用的接口不存在,请检查(-8)',
            'optionsvalueError' => 'options value不匹配(-9)',
            'productnotexists' => '产品不存在(-10)',
            'conditionsEmpty' => '请选择条件(-11)',
            'countryEmpty' => '请选择国家(-12)',
            'dbError' => '操作数据库错误(-13)',
            'collectionNotExists' => 'collection不存在(-14)',
            'nameEmpty' => '名称不能为空(-15)',
            'startTimeError' => '开始时间格式错误(-16)',
            'endTimeError' => '结束时间格式错误(-17)',
            'startendTimeError' => '结束时间不能早于开始时间(-18)',
            'discountTypeError' => '折扣类型错误(-19)',
            'startNotEdit' => '已经开始倒计时不能编辑(-20)',
            'countdownidError' => 'countdown id错误(-21)',
            'collectionidError' => 'collection id错误(-22)',
            'productidError' => 'product id错误(-23)',
            'timestampEmpty' => '时间戳参数错误(-24)',
            'ipEmpty' => 'ip参数错误(-25)',
            'nonceEmpty' => 'nonce参数错误(-26)',
            'idError' => '查询记录时id错误(-27)',
            'typeError' => 'type参数错误(-28)',
            '_idError' => 'ID参数错误(-29)',
            'contidionLinkError' => '条件中link字段错误(-30)',
            'contidionFieldsError' => '条件中fields字段错误(-31)',
            'contidionValuesError' => '条件中values字段错误(-32)',
            'countdownError' => 'countdown参数错误(-33)',
            'productStatusError' => '产品状态错误(-34)',
            'pleaseSetCollection' => '请先设置collection(-35)',
            'collectionError' => 'collection错误(-36)',
            'seourlExists' => 'seo_url存在(-37)',
            'selectSourceCountry' => '请选择源国家(-38)',
            'syncCountry' => '请选择需要同步的国家(-39)',
            'priceltError' => '原价应该大于售价(-40)',
            'priceError' => '价格，原价或成本价错误(-41)',
            'weightError' => '重量错误(-42)',
            'productExists' => '产品已经存在(-43)',
            'googlefeedcolorEmpty' => 'google feed color不能为空(-44)',
            'googlefeedsizeEmpty' => 'google feed size不能为空(-45)',
            'googlefeedgenderError' => 'google feed gender错误(-46)',
            'googlefeedagegroup' => 'google feed age group错误(-47)',
            'relativeproductError' => 'relativeproduct SKU错误(-48)',
            'typeExists' => 'type已经存在(-49)',
            'notsetcountry' => '新系统未设置国家信息(-50)',
            'optionsvalueCharsError' => 'optionsvalue不能包含逗号字符(-51)',
            'optionsError' => 'options错误(-52)',
            'seourlEmpty' => 'seo_url不能为空(-53)'
        );
        $methodslist = $this->getMethods(get_class($this));
        $acts = $this->uri->segment(2);
        if (!in_array($acts, $methodslist)) {
            exit(json_encode(array('status' => 404, 'info' => $this->error['methodError'])));
        }
        $parameter = array(
            'timestamp' => $this->rewrite_post('timestamp', false),
            'ip' => $this->rewrite_post('ip', false),
            'nonce' => $this->rewrite_post('nonce', false),
            'key' => $this->key
        );
        if (empty($parameter['timestamp'])) {
            exit(json_encode(array('status' => 404, 'info' => $this->error['timestampEmpty'])));
        }
        if (empty($parameter['ip'])) {
            exit(json_encode(array('status' => 404, 'info' => $this->error['ipEmpty'])));
        }
        if (empty($parameter['nonce'])) {
            exit(json_encode(array('status' => 404, 'info' => $this->error['nonceEmpty'])));
        }
        ksort($parameter);
        $parameters = join('&', $parameter);
        $this->signature = sha1($parameters);
        $signature = $this->rewrite_post('signature', false);
        if ($signature != $this->signature) {
            exit(json_encode(array('status' => 401, 'info' => $this->error['signatureError'])));
        }
        $noCountry = array(
            'addcollection',
            'addcountdown',
            'updatecountdown',
            'editcountdown',
            'deletecountdown',
            'collctiontagselect',
            'addproductype',
            'updateproductype',
            'deleteproductype',
            'changecountdownstatus',
            'deletecollection',
            'changeproductstatus',
            'productprice'
        );
        if (!in_array($acts, $noCountry)) {
            $countrylist = array_keys($this->_category['countryList']);
            if (empty($countrylist)) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['notsetcountry'])));
            }
            if ($acts == 'updateproduct' || $acts == 'publishproduct' || $acts == 'localupdateproduct') {
                $this->country = $this->rewrite_post('product', false)['country'];
            } else {
                $this->country = $this->rewrite_post('country', false);
            }
            if (is_array($this->country)) {
                $diff = array_diff($this->country, $countrylist);
                if (!empty($diff)) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['countryError'])));
                }
            } elseif (!in_array($this->country, $countrylist)) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['countryError'])));
            }
        }
        $this->field_map = array(
            'product_type' => 'type',
            'images' => 'image',
            'tags' => 'tag',
            'body_html' => 'description',
        );
        $this->returnfield_map = array(
            'description' => 'body_html',
            'created_at' => 'create_time',
            'updated_at' => 'update_time',
            '_id' => 'id'
        );
    }

    private function getMethods($classname, $access = null) {
        $class = new \ReflectionClass($classname);
        $methods = $class->getMethods();
        $returnArr = array();
        foreach ($methods as $value) {
            if ($value->class == $classname) {
                if ($value->name == 'getMethods' || $value->name == '__construct')
                    continue;
                if ($access != null) {
                    $methodAccess = new \ReflectionMethod($classname, $value->name);
                    switch ($access) {
                        case 'public':
                            if ($methodAccess->isPublic())
                                $returnArr[$value->name] = 'public';
                            break;
                        case 'protected':
                            if ($methodAccess->isProtected())
                                $returnArr[$value->name] = 'protected';
                            break;
                        case 'private':
                            if ($methodAccess->isPrivate())
                                $returnArr[$value->name] = 'private';
                            break;
                        case 'final':
                            if ($methodAccess->isFinal())
                                $returnArr[$value->name] = 'final';
                            break;
                    }
                }else {
                    array_push($returnArr, $value->name);
                }
            }
        }
        return $returnArr;
    }

    public function publishproduct() {
        // 获取数据
        $datas = $this->rewrite_post();
        $data = $datas['product'];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if (isset($this->field_map[$k])) {
                    $data[$this->field_map[$k]] = $v;
                    unset($data[$k]);
                }
            }
        }
        if (isset($data['status']) && !in_array($data['status'], array(1, 2, 3))) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['productStatusError'])));
        } elseif (isset($data['status'])) {
            $data['status'] = $data['status'] == 1 ? 2 : ($data['status'] == 2 ? 1 : 3);
        } else {
            $data['status'] = 1;
        }
        if (isset($data['freebies']) && $data['freebies'] > 0) {
            $data['freebies'] = 1;
        } else {
            $data['freebies'] = 0;
        }
        if (isset($data['GF_enable']) && $data['GF_enable'] > 0) {
            $data['GF_enable'] = 1;
            if (empty($data['GF_color'])) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedcolorEmpty'])));
            }
            if (empty($data['GF_size'])) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedsizeEmpty'])));
            }
            if (!in_array($data['GF_gender'], array('male', 'female', 'unisex'))) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedgenderError'])));
            }
            if (!in_array($data['GF_agegroup'], array('newborn', 'infant', 'toddler', 'kids', 'adult'))) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedagegroupError'])));
            }
        } else {
            $data['GF_enable'] = 0;
        }
        if (isset($data['diy']) && $data['diy'] == 1) {
            $data['diy'] = 1;
        } else {
            $data['diy'] = 0;
        }
        $this->load->model('product_model');
        if (isset($data['relativeproduct']) && !empty($data['relativeproduct'])) {
            $sku_list = $this->product_model->distinct($this->country, 'sku');
            if (!is_array($data['relativeproduct'])) {
                $data['relativeproduct'] = explode(',', $data['relativeproduct']);
            }
            $data['relativeproduct'] = array_intersect($data['relativeproduct'], $sku_list);
            if (empty($data['relativeproduct'])) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['relativeproductError'])));
            }
        } else {
            $data['relativeproduct'] = array();
        }
        // 价格区间
        $tag = array(
            '0' => '$0 - $9.99',
            '1' => '$10 - $19.99',
            '2' => '$20 - $29.99',
            '3' => '$30 - $39.99',
            '4' => '$40 - $69.99',
            '5' => '$70 - $99.99',
            '6' => '$100 - $199.99',
            '7' => '$200+',
        );
        $_id = new MongoId();
        if (isset($data['_id']) && strlen($data['_id']) == 24) {
            $data['_id'] = new MongoId($data['_id']);
        } else {
            $data['_id'] = $_id;
        }
        // 产品主图
        // 初始化上传数据
        if (empty($data['title'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['titleEmpty'])));
        }
        if ($data['sku'] == NULL) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['skuEmpty'])));
        }
        if (empty($data['cost'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['costEmpty'])));
        }
        if (empty($data['type'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['typeEmpty'])));
        }
        $pailiezuhe = 0;
        // 生成默认映射名称
        if (isset($data['variants']) && $data['variants'][0]['option'] != NULL) {
            $data['children'] = 1;
            $pailiezuhe = 1;
            foreach ($data['variants'] as $key => $vo) {
                $vCount = count(explode(',', $data['variants'][$key]['value']));
                $data['variants'][$key]['option'] = is_array($vo['option']) ? $vo['option'][0] : $vo['option'];
                $data['variants'][$key]['value'] = is_array($vo['value']) ? join(',', $vo['value']) : $vo['value'];
                if (/* strpos($data['variants'][$key]['value'], '"') !== false || */substr_count($data['variants'][$key]['value'], ',') != $vCount - 1) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['optionsvalueCharsError'])));
                }
                $data['variants'][$key]['option_map'] = is_array($vo['option_map']) ? $vo['option_map'][0] : $vo['option_map'];
                $data['variants'][$key]['value_map'] = is_array($vo['value_map']) ? join(',', $vo['value_map']) : $vo['value_map'];
                if ($vCount != count(explode(',', $data['variants'][$key]['value_map']))) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['optionsvalueError'])));
                }
                $pailiezuhe *= $vCount;
            }
        }
        $detailscount = isset($data['details']) ? count($data['details']) : 0;
        if ($pailiezuhe != $detailscount) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['optionsError'])));
        }
        if (empty($data['seo']['title'])) {
            $data['seo']['title'] = $data['title'];
        }
        if (empty($data['seo_url'])) {
            $data['seo_url'] = $data['title'];
        }
        $data['seo_url'] = str_replace(array('&', '#', '%', '"', '?', '/', '\'', '\\', ' '), array('', '', '', '', '', '', '', '', '-'), $data['seo_url']);
        $data['seo_url'] = trim($data['seo_url'], '-');
        $data['seo_url'] = preg_replace("/\-+/", "-", $data['seo_url']);
        $data['description'] = $this->replachtmlimage($data['description']);
        $data['specification'] = $this->replachtmlimage($data['specification']);
        $data['topreview'] = $this->replachtmlimage($data['topreview']);
        // 载入模型
        $this->load->model('category_model');
        $this->load->model('shipformula_model');
        $this->load->model('country_model');
        $this->load->model('collection_model');
        $this->load->model('countdown_model');
        $this->load->model('tag_model');
        $this->load->model('dropdown_model');
        $category = $this->category_model->getInfoById($data['type']);
        if (!$category) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['typeError'])));
        }
        if (!empty($data['countdown'])) {
            $countdown = $this->countdown_model->getInfoById($data['countdown']);
            if (!$countdown || (!empty($countdown) && $countdown['status'] == 1)) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['countdownError'])));
            }
        }
        //$countries = $this->country_model->getCountryCodeSet();
        $countries = is_string($this->country) ? explode(',', $this->country) : $this->country;
        //$rate = $this->country_model->getCountryList('au_rate');
        if ($data['sold']['init']) {
            $data['sold']['total'] = $data['sold']['init'];
        } else {
            $data['sold']['total'] = 0;
            $data['sold']['init'] = 0;
        }
        $data['sold']['number'] = 0;
        $i = 0;
        if (!empty($data['image']) || (isset($data['thumb'][0]) && !empty($data['thumb'][0]))) {
            $root = dirname(dirname(dirname((__DIR__))));
            $url = $root . '/uploads/product/' . $data['sku'];
            if (!file_exists($url)) {
                mkdir($url, 0777, true);
            }
        }
        if (!empty($data['image'])) {
            if (is_array($data['image'])) {
                $image = $data['image'];
            } else {
                $image = explode(',', $data['image']);
            }
            $this->product_model->product_image_queue($url, $image);
            if (!empty($image)) {
                foreach ($image as $k => $v) {
                    $data['pics'][$k]['img'] = '/product/' . $data['sku'] . '/' . basename($v);
                    $data['pics'][$k]['sort'] = $k + 1;
                }
            }
            $data['image'] = '/product/' . $data['sku'] . '/' . basename($image[0]);
        }
        if (isset($data['thumb'][0]) && !empty($data['thumb'][0])) {
            $this->product_model->product_image_queue($url, $data['thumb'], $data['sku']);
        }
        //$this->db->trans_start();
        $new = $data;
        if (floatval($new['price']) <= 0 || floatval($new['original']) <= 0 || floatval($new['cost']) <= 0) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['priceError'])));
        }
        if (floatval($new['price']) > floatval($new['original'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['priceltError'])));
        }
        if ((int) $new['weight'] <= 0) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['weightError'])));
        }
        // 如果有子属性
        if (isset($new['details']) && !empty($new['details'])) {
            $i = 0;
            $new['cost']*=100;
            // 40%毛利率 = / 0.6
            if ($new['gross']) {
                $gross = round(1 - $new['gross'] / 100, 1);
                if ($gross <= 0 || $gross >= 1) {
                    $gross = 0.6;
                }
            } else {
                $new['gross'] = 40;
            }
            $weight = $new['weight'];
            $new['original'] *= 100;
            //$new['bundle'] *= 100;
            $new['price'] *= 100;
            if (isset($new['bundle']) && $new['bundle'] > 0) {
                $new['bundle'] = $new['bundle'] * 100;
            } else {
                $new['bundle'] = ceil($new['price'] * 0.85);
            }
//                $ship = $this->shipformula_model->calculateShipping($country, $weight);
            // 获取子属性
            foreach ($new['details'] as $key => $vo) {
                if (floatval($vo['price']) <= 0 || floatval($vo['original'] <= 0) || floatval($vo['cost'] <= 0)) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['priceError'])));
                }
                if (floatval($vo['price']) > floatval($vo['original'])) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['priceltError'])));
                }
                if ((int) $vo['weight'] <= 0) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['weightError'])));
                }
                $cost = $vo['cost'] * 100;
//                    $price = ceil(($cost + $ship * 100) / $gross / $this->RMBtoAU * $rate[$country]);
                // 虚假售价 220%
//                    $original = ceil($price * 2.2);
                // 默认捆绑售价 85%
                $price = $vo['price'] * 100;
                if (isset($vo['bundle']) && $vo['bundle'] > 0) {
                    $bundle = $vo['bundle'] * 100;
                } else {
                    $bundle = ceil($price * 0.85);
                }
                if (isset($vo['original']) && $vo['original'] > 0) {
                    $original = $vo['original'] * 100;
                } else {
                    $original = ceil($price * 2.2);
                }
                if (!isset($vo['status'])) {
                    $status = 1;
                } else {
                    $status = $vo['status'] == 1 ? 2 : ($vo['status'] == 2 ? 1 : 3);
                }
                if (isset($vo['weight']) && $vo['weight'] > 0) {
                    $weight = $vo['weight'];
                } else {
                    $weight = '';
                }
                $new['details'][$key]['cost'] = $cost;
                $new['details'][$key]['price'] = $price;
                $new['details'][$key]['original'] = $original;
                $new['details'][$key]['bundle'] = $bundle;
                $new['details'][$key]['weight'] = $weight;
                $new['details'][$key]['status'] = $status;
                $new['details'][$key]['stock'] = 0;
            }
            $details = $new['details'];
            $details = array_values(arr_sort($details, 'price', 'asc'));
            $new['price'] = $details[0]['price'];
            $new['original'] = $details[0]['original'];
            $new['bundle'] = $details[0]['bundle'];
            $price = $details[0]['price'];
            // 如果没有子属性
        } else {
            $new['cost'] *= 100;
            $cost = $new['cost'];
            $weight = $new['weight'];
            //$ship = $this->shipformula_model->calculateShipping($country, $weight);
            // 40%毛利率 = / 0.6
            if ($new['gross']) {
                $gross = round(1 - $new['gross'] / 100, 1);
                if ($gross <= 0 || $gross >= 1) {
                    $gross = 0.6;
                }
            } else {
                $new['gross'] = 40;
            }
            //$new['price'] = ceil(($cost + $ship * 100) / $gross / $this->RMBtoAU * $rate[$country]);
            $price = $new['price'] = $new['price'] * 100;
            // 虚假售价 220%
            if (isset($new['original']) && $new['original'] > 0) {
                $new['original']*=100;
            } else {
                $new['original'] = ceil($new['price'] * 2.2);
            }
            // 默认捆绑售价 85%
            $new['bundle'] = ceil($new['price'] * 0.85);
        }
        // 用最低价格计算tag1标签
        if ($price >= 0 && $price <= 999) {
            $new['tag']['Tag1'] = $tag[0];
        } else if ($price >= 1000 && $price <= 1999) {
            $new['tag']['Tag1'] = $tag[1];
        } else if ($price >= 2000 && $price <= 2999) {
            $new['tag']['Tag1'] = $tag[2];
        } else if ($price >= 3000 && $price <= 3999) {
            $new['tag']['Tag1'] = $tag[3];
        } else if ($price >= 4000 && $price <= 6999) {
            $new['tag']['Tag1'] = $tag[4];
        } else if ($price >= 7000 && $price <= 9999) {
            $new['tag']['Tag1'] = $tag[5];
        } else if ($price >= 10000 && $price <= 19999) {
            $new['tag']['Tag1'] = $tag[6];
        } else {
            $new['tag']['Tag1'] = $tag[7];
        }
        // 组装B\C模式售价
        if (isset($new['bnumber']) && isset($new['bprice']) && $new['bnumber'] != NULL && $new['bprice'] != NULL) {
            $bnumber = explode(',', $new['bnumber']);
            $bprice = explode(',', $new['bprice']);
            $i = 0;
            foreach ($bnumber as $key => $vo) {
                $new['plural'][$i]['number'] = $bnumber[$key];
                $new['plural'][$i]['price'] = $bprice[$key] * 100;
                $i++;
            }
            if ($i > 0) {
                $new['bundletype'] = 1;
            }
        } else {
            $new['plural'] = array();
            $new['bundletype'] = 0;
        }
        // 废弃，自动计算Tag1标签，不再支持手动输入
        // $new['tag']['Tag1'] = explode(',',$new['tag']['Tag1']);
        if (isset($new['tag']['Tag2']) && !empty($new['tag']['Tag2'])) {
            if (!is_array($new['tag']['Tag2'])) {
                $new['tag']['Tag2'] = explode(',', $new['tag']['Tag2']);
            }
            $new['tag']['Tag2'] = array_unique($new['tag']['Tag2']);
        } else {
            $new['tag']['Tag2'] = array();
        }
        if (isset($new['tag']['Tag3']) && !empty($new['tag']['Tag3'])) {
            if (!is_array($new['tag']['Tag3'])) {
                $new['tag']['Tag3'] = explode(',', $new['tag']['Tag3']);
            }
            $new['tag']['Tag3'] = array_unique($new['tag']['Tag3']);
        } else {
            $new['tag']['Tag3'] = array();
        }
        foreach ($countries as $country) {
            $hasExists = $this->product_model->hasExists($country, '', $new['sku'], $new['seo_url']);
            if ($hasExists) {
                $hasExists = array_values(iterator_to_array($hasExists));
                $item_info = array('id' => (string) $hasExists[0]['_id'], 'sku' => $new['sku']);
                exit(json_encode(array('status' => 400, 'info' => $this->error['skuorseourlExists'], 'item_info' => $item_info)));
            }
            $old = $this->product_model->findOne($country, $new['_id']);
            if ($old) {
                //exit(json_encode(array('status' => 400, 'info' => $this->error['productExists'])));
                $new['_id'] = $_id;
            }
            if (!empty($new['collection'])) {
                $collection_id_array = iterator_to_array($this->dropdown_model->collection($country, array('_id' => 1)));
                $tmp = array();
                if ($collection_id_array) {
                    foreach ($collection_id_array as $k1 => $v1) {
                        $tmp[] = $v1['_id'];
                    }
                }
                if (empty($tmp)) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['pleaseSetCollection'])));
                } else {
                    if (!is_array($new['collection'])) {
                        $new['collection'] = explode(',', $new['collection']);
                    }
                    $int = array_intersect($new['collection'], $tmp);
                    if (empty($int)) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['collectionError'])));
                    } else {
                        $new['collection'] = join(',', $int);
                    }
                }
            }
            if (!empty($new['tag'])) {
                $this->tag_model->addTag($country, $new['tag']);
            }
            $this->collection_model->addOneProduct($new['_id'], $country, $new['collection']);
            $this->countdown_model->addOneProduct($new['_id'], $country, $new['countdown']);
            $this->product_model->insert($country, $new);
        }
        //$this->db->trans_complete();
        exit(json_encode(array('status' => 200, 'info' => (string) $data['_id'])));
    }

    function updateproduct() {
        $datas = $this->rewrite_post();
        $data = $datas['product'];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if (isset($this->field_map[$k])) {
                    $data[$this->field_map[$k]] = $v;
                    unset($data[$k]);
                }
            }
        }
        if (isset($data['status']) && !in_array($data['status'], array(1, 2, 3))) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['productStatusError'])));
        } elseif (isset($data['status'])) {
            $data['status'] = $data['status'] == 1 ? 2 : ($data['status'] == 2 ? 1 : 3);
        } else {
            $data['status'] = 1;
        }
        if (isset($data['freebies']) && $data['freebies'] > 0) {
            $data['freebies'] = 1;
        } else {
            $data['freebies'] = 0;
        }
        if (isset($data['GF_enable']) && $data['GF_enable'] > 0) {
            $data['GF_enable'] = 1;
            if (empty($data['GF_color'])) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedcolorEmpty'])));
            }
            if (empty($data['GF_size'])) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedsizeEmpty'])));
            }
            if (!in_array($data['GF_gender'], array('male', 'female', 'unisex'))) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedgenderError'])));
            }
            if (!in_array($data['GF_agegroup'], array('newborn', 'infant', 'toddler', 'kids', 'adult'))) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedagegroupError'])));
            }
        } else {
            $data['GF_enable'] = 0;
        }
        if (isset($data['diy']) && $data['diy'] == 1) {
            $data['diy'] = 1;
        } else {
            $data['diy'] = 0;
        }
        if (empty($data['seo']['title'])) {
            $data['seo']['title'] = $data['title'];
        }
        if (empty($data['seo_url'])) {
            $data['seo_url'] = $data['title'];
        }
        $data['seo_url'] = str_replace(array('&', '#', '%', '"', '?', '/', '\'', '\\', ' '), array('', '', '', '', '', '', '', '', '-'), $data['seo_url']);
        $data['seo_url'] = trim($data['seo_url'], '-');
        $data['seo_url'] = preg_replace("/\-+/", "-", $data['seo_url']);
        if (isset($data['creator']))
            unset($data['creator']);
        $data['description'] = $this->replachtmlimage($data['description']);
        $data['specification'] = $this->replachtmlimage($data['specification']);
        $data['topreview'] = $this->replachtmlimage($data['topreview']);
        $this->load->model('product_model');
        if (isset($data['relativeproduct']) && !empty($data['relativeproduct'])) {
            $sku_list = $this->product_model->distinct($this->country, 'sku');
            if (!is_array($data['relativeproduct'])) {
                $data['relativeproduct'] = explode(',', $data['relativeproduct']);
            }
            $data['relativeproduct'] = array_intersect($data['relativeproduct'], $sku_list);
            if (empty($data['relativeproduct'])) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['relativeproductError'])));
            }
        } else {
            $data['relativeproduct'] = array();
        }
        // 价格区间
        $tag = array(
            '0' => '$0 - $9.99',
            '1' => '$10 - $19.99',
            '2' => '$20 - $29.99',
            '3' => '$30 - $39.99',
            '4' => '$40 - $69.99',
            '5' => '$70 - $99.99',
            '6' => '$100 - $199.99',
            '7' => '$200+',
        );
        $this->load->model('category_model');
        $this->load->model('collection_model');
        $this->load->model('countdown_model');
        $this->load->model('tag_model');
        $this->load->model('dropdown_model');
        // Title及Description转义
        $strip_tags_desc = htmlspecialchars(substr(strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"), ' ', $data['description']))), 0, 160));
        $data['title'] = htmlspecialchars($data['title'], ENT_COMPAT);
        $data['description'] = htmlspecialchars($data['description'], ENT_COMPAT);
        $data['specification'] = htmlspecialchars($data['specification'], ENT_COMPAT);
        $data['topreview'] = htmlspecialchars($data['topreview'], ENT_COMPAT);
        $data['seo']['title'] = isset($data['seo']['title']) ? htmlspecialchars($data['seo']['title'], ENT_COMPAT) : '';
        $data['seo']['description'] = (isset($data['seo']['description']) && !empty($data['seo']['description'])) ? htmlspecialchars(substr(strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"), ' ', $data['seo']['description']))), 0, 160)) : $strip_tags_desc;
        $data['seo']['keyword'] = isset($data['seo']['keyword']) ? htmlspecialchars($data['seo']['keyword'], ENT_COMPAT) : '';
        $data['shopping_feed'] = htmlspecialchars($data['shopping_feed'], ENT_COMPAT);
        if (empty($data['title'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['titleEmpty'])));
        }
        if ($data['sku'] == NULL) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['skuEmpty'])));
        }
        if (empty($data['cost'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['costEmpty'])));
        }
        if (empty($data['type'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['typeEmpty'])));
        }
        $hasExists = $this->product_model->hasExists($this->country, $data['_id'], $data['sku'], $data['seo_url']);
        if ($hasExists) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['skuorseourlExists'])));
        }
        if (floatval($data['price']) <= 0 || floatval($data['original']) <= 0 || floatval($data['cost']) <= 0) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['priceError'])));
        }
        if (floatval($data['price']) > floatval($data['original'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['priceltError'])));
        }
        if ((int) $data['weight'] <= 0) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['weightError'])));
        }
        if (!empty($data['collection'])) {
            $collection_id_array = iterator_to_array($this->dropdown_model->collection($this->country, array('_id' => 1)));
            $tmp = array();
            if ($collection_id_array) {
                foreach ($collection_id_array as $k1 => $v1) {
                    $tmp[] = $v1['_id'];
                }
            }
            if (empty($tmp)) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['pleaseSetCollection'])));
            } else {
                if (!is_array($data['collection'])) {
                    $data['collection'] = explode(',', $data['collection']);
                }
                $int = array_intersect($data['collection'], $tmp);
                if (empty($int)) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['collectionError'])));
                } else {
                    $data['collection'] = join(',', $int);
                }
            }
        }
        if (!empty($data['countdown'])) {
            $countdown = $this->countdown_model->getInfoById($data['countdown']);
            if (!$countdown || (!empty($countdown) && $countdown['status'] == 1)) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['countdownError'])));
            }
        }
        $category = $this->category_model->getInfoById($data['type']);
        if (!$category) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['typeError'])));
        }
        if (!empty($data['image']) || (isset($data['thumb'][0]) && !empty($data['thumb'][0]))) {
            $root = dirname(dirname(dirname((__DIR__))));
            $url = $root . '/uploads/product/' . $data['sku'];
            if (!file_exists($url)) {
                mkdir($url, 0777, true);
            }
        }
        if (!empty($data['image'])) {
            if (is_array($data['image'])) {
                $image = $data['image'];
            } else {
                $image = explode(',', $data['image']);
            }
            $this->product_model->product_image_queue($url, $image);
            if (!empty($image)) {
                foreach ($image as $k => $v) {
                    $data['pics'][$k]['img'] = '/product/' . $data['sku'] . '/' . basename($v);
                    $data['pics'][$k]['sort'] = $k + 1;
                }
            }
            $data['image'] = '/product/' . $data['sku'] . '/' . basename($image[0]);
        }
        if (isset($data['thumb'][0]) && !empty($data['thumb'][0])) {
            $this->product_model->product_image_queue($url, $data['thumb'], $data['sku']);
        }
        // 主表价格
        $data['cost']*=100;
        $data['price']*=100;
        // 定义最低价，后面如果有子属性会覆盖
        $price = $data['price'];
        if (isset($data['original']) && $data['original'] > 0) {
            $data['original']*=100;
        } else {
            $data['original'] = ceil($price * 2.2);
        }
        if (isset($data['bundle']) && $data['bundle'] > 0) {
            $data['bundle']*=100;
        } else {
            $data['bundle'] = ceil($price * 0.85);
        }
        // Mapping映射
        $data['children'] = 0;
        $pailiezuhe = 0;
        if (isset($data['variants']) && !empty($data['variants'])) {
            $variants = array();
            $i = 0;
            $pailiezuhe = 1;
            foreach ($data['variants'] as $key => $vo) {
                if ($vo['option'] != NULL) {
                    if (!is_array($vo['value'])) {
                        $value = explode(',', $vo['value']);
                    } else {
                        $value = $vo['value'];
                    }
                    $vCount = count($value);
                    if (!is_array($vo['value_map'])) {
                        $value_map = explode(',', $vo['value_map']);
                    } else {
                        $value_map = $vo['value_map'];
                    }
                    $mCount = count($value_map);
                    if ($vCount != $mCount) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['optionsvalueError'])));
                    }
                    if (is_array($vo['value'])) {
                        $vo['value'] = join(',', $vo['value']);
                    }
                    if (/* strpos($vo['value'], '"') !== false || */substr_count($vo['value'], ',') != $vCount - 1) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['optionsvalueCharsError'])));
                    }
                    if (is_array($vo['value_map'])) {
                        $vo['value_map'] = join(',', $vo['value_map']);
                    }
                    if (is_array($vo['option'])) {
                        $vo['option'] = $vo['option'][0];
                    }
                    if (is_array($vo['option_map'])) {
                        $vo['option_map'] = $vo['option_map'][0];
                    }
                    $variants[$i] = $vo;
                    $i++;
                    $pailiezuhe *= $vCount;
                }
            }
            $data['variants'] = $variants;
        }
        $detailscount = isset($data['details']) ? count($data['details']) : 0;
        if ($pailiezuhe != $detailscount) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['optionsError'])));
        }
        if (isset($data['details'])) {
            $details = array();
            $j = 0;
            foreach ($data['details'] as $key => $vo) {
                if ($vo['sku'] != NULL) {
                    if (floatval($vo['price']) <= 0 || floatval($vo['original'] <= 0) || floatval($vo['cost'] <= 0)) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['priceError'])));
                    }
                    if (floatval($vo['price']) > floatval($vo['original'])) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['priceltError'])));
                    }
                    if ((int) $vo['weight'] <= 0) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['weightError'])));
                    }
                    $vo['cost'] *= 100;
                    $vo['price'] *= 100;
                    if (isset($vo['original']) && $vo['original'] > 0) {
                        $vo['original'] *= 100;
                    } else {
                        $vo['original'] = ceil($vo['price'] * 2.2);
                    }
                    if (isset($vo['bundle']) && $vo['bundle'] > 0) {
                        $vo['bundle']*=100;
                    } else {
                        $vo['bundle'] = ceil($vo['price'] * 0.85);
                    }
                    if (!isset($vo['status'])) {
                        $vo['status'] = 1;
                    } else {
                        $vo['status'] = $vo['status'] == 1 ? 2 : ($vo['status'] == 2 ? 1 : 3);
                    }
                    if (isset($vo['weight']) && $vo['weight'] > 0) {
                        $vo['weight'] = $vo['weight'];
                    } else {
                        $vo['weight'] = '';
                    }
                    $vo['stock'] = 0;
                    $details[$j] = $vo;
                    $j++;
                }
            }
            $data['details'] = $details;
            if ($i > 0 && $j > 0) {
                $data['children'] = 1;
                // 用子属性里最低价格覆盖price变量
                //array_multisort($details, SORT_ASC);
                $details = array_values(arr_sort($details, 'price', 'asc'));
                $data['price'] = $price = $details[0]['price'];
                $data['original'] = $details[0]['original'];
                $data['bundle'] = $details[0]['bundle'];
            }
        }
        // 用最低价格计算tag1标签
        if ($price >= 0 && $price <= 999) {
            $data['tag']['Tag1'] = $tag[0];
        } else if ($price >= 1000 && $price <= 1999) {
            $data['tag']['Tag1'] = $tag[1];
        } else if ($price >= 2000 && $price <= 2999) {
            $data['tag']['Tag1'] = $tag[2];
        } else if ($price >= 3000 && $price <= 3999) {
            $data['tag']['Tag1'] = $tag[3];
        } else if ($price >= 4000 && $price <= 6999) {
            $data['tag']['Tag1'] = $tag[4];
        } else if ($price >= 7000 && $price <= 9999) {
            $data['tag']['Tag1'] = $tag[5];
        } else if ($price >= 10000 && $price <= 19999) {
            $data['tag']['Tag1'] = $tag[6];
        } else {
            $data['tag']['Tag1'] = $tag[7];
        }
        $old = $this->product_model->findOne($this->country, $data['_id']);
        if (empty($old)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['productnotexists'])));
        }
        $data['sold']['total'] = $data['sold']['init'] + $old['sold']['number'];
        $data['sold']['number'] = $old['sold']['number'];
        // 组装B\C模式售价
        if (isset($data['bnumber']) && isset($data['bprice']) && $data['bnumber'] != NULL && $data['bprice'] != NULL) {
            $bnumber = explode(',', $data['bnumber']);
            $bprice = explode(',', $data['bprice']);
            $i = 0;
            foreach ($bnumber as $key => $vo) {
                $data['plural'][$i]['number'] = $bnumber[$key];
                $data['plural'][$i]['price'] = $bprice[$key] * 100;
                $i++;
            }
            if ($i > 0) {
                $data['bundletype'] = 1;
            }
        } else {
            $data['plural'] = array();
            $data['bundletype'] = 0;
        }
        // Tag1由价格自行决定，不再支持修改
        if (isset($data['tag']['Tag2']) && !empty($data['tag']['Tag2'])) {
            if (!is_array($data['tag']['Tag2'])) {
                $data['tag']['Tag2'] = explode(',', $data['tag']['Tag2']);
            }
            $data['tag']['Tag2'] = array_unique($data['tag']['Tag2']);
        } else {
            $data['tag']['Tag2'] = array();
        }
        if (isset($data['tag']['Tag3']) && !empty($data['tag']['Tag3'])) {
            if (!is_array($data['tag']['Tag3'])) {
                $data['tag']['Tag3'] = explode(',', $data['tag']['Tag3']);
            }
            $data['tag']['Tag3'] = array_unique($data['tag']['Tag3']);
        } else {
            $data['tag']['Tag3'] = array();
        }
        //$this->db->trans_start();
        if (!empty($data['tag'])) {
            $rstag = $this->tag_model->upTag($this->country, $old['tag'], $data['tag']);
        }
        // Update
        $this->collection_model->addOneProduct($data['_id'], $this->country, $data['collection']);
        if (!$data['countdown']) {
            $this->countdown_model->clearOneProduct($data['_id'], $this->country);
        } else {
            $this->countdown_model->addOneProduct($data['_id'], $this->country, $data['countdown']);
        }
        $this->product_model->update($this->country, $data);
        //$this->db->trans_complete();
        exit(json_encode(array('status' => 200, 'info' => '')));
    }

    function editproduct() {
        $_id = $this->rewrite_post('id');
        if (empty($_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['productidError'])));
        }
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('collection_model');
        $this->load->model('dropdown_model');
        $this->load->model('countdown_model');
        $this->page['pro'] = $this->product_model->findOne($this->country, $_id);
        if (empty($this->page['pro'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['productnotexists'])));
        }
        $data = $this->category_model->getInfoByID($this->page['pro']['type']);
        if (is_array($data) && isset($data['title'])) {
            $this->page['pro']['product_type'] = $data['title'];
        } else {
            $this->page['pro']['product_type'] = '';
        }
        $data = $this->collection_model->getListByProductId($this->country, $_id);
        $arr = array();
        if ($data) {
            foreach ($data as $vo) {
                $arr[] = $vo['_id'];
            }
        }
        if (!empty($this->page['pro']['pics'])) {
            foreach ($this->page['pro']['pics'] as $k => $v) {
                $this->page['pro']['images'][$k] = array('src' => IMAGE_DOMAIN . $v['img'], 'position' => $v['sort']);
            }
        }
        if (@fopen(IMAGE_DOMAIN . 'product/' . $this->page['pro']['sku'] . '/' . $this->page['pro']['sku'] . '.jpg', 'r')) {
            $this->page['pro']['thumb'] = IMAGE_DOMAIN . 'product/' . $this->page['pro']['sku'] . '/' . $this->page['pro']['sku'] . '.jpg';
        }
        $this->page['pro']['status'] = $this->page['pro']['status'] == 1 ? 2 : ($this->page['pro']['status'] == 2 ? 1 : 3);
        unset($this->page['pro']['pics']);
        if (!empty($this->page['pro']['variants'])) {
            foreach ($this->page['pro']['variants'] as $k => $v) {
                $this->page['pro']['options'][$k] = array('name' => $v['option'], 'values' => $v['value'], 'name_map' => $v['option_map'], 'values_map' => $v['value_map']);
            }
        }
        unset($this->page['pro']['variants']);
        if (!empty($this->page['pro']['details'])) {
            foreach ($this->page['pro']['details'] as $k => $v) {
                $this->page['pro']['variants'][$k] = array(
                    'title' => $v['option'], 'price' => $v['price'], 'sku' => $v['sku'], 'stock' => $v['stock'], 'status' => $v['status'] == 1 ? 2 : ($v['status'] == 2 ? 1 : 0), 'original' => $v['original'], 'bundle' => $v['bundle']
                );
            }
        }
        unset($this->page['pro']['details']);
        $this->page['cur_collection'] = $arr;
        $this->page['product_type_list'] = iterator_to_array($this->dropdown_model->category());
        $this->page['collection'] = iterator_to_array($this->dropdown_model->collection($this->country));
        $this->page['countdown'] = $this->dropdown_model->countDown();
        $this->page['cur_countdown'] = $this->countdown_model->getInfoByProductId($this->country, $_id);
        $this->page['last'] = $this->product_model->last($this->country, $this->page['pro']['_id']);
        $this->page['next'] = $this->product_model->next($this->country, $this->page['pro']['_id']);
        $d = $this->collection_model->getCollectionByProductId($this->country, $_id);
        $this->page['collection_url'] = '';
        if (!empty($d)) {
            foreach ($d as $key => $value) {
                $this->page['collection_url'] = $value['seo_url'];
                break;
            }
        }
        if (is_array($this->page['pro']['tag']['Tag1'])) {
            $this->page['pro']['tag']['Tag1'] = isset($this->page['pro']['tag']['Tag1'][0]) ? $this->page['pro']['tag']['Tag1'][0] : '';
        }
        unset($this->page['template'], $this->page['pro']['oid'], $this->page['pro']['type'], $this->page['pro']['bundleid'], $this->page['pro']['relation']);
        if (!empty($this->page['pro'])) {
            foreach ($this->page['pro'] as $k => $v) {
                if (isset($this->returnfield_map[$k])) {
                    $this->page['pro'][$this->returnfield_map[$k]] = $v;
                    unset($this->page['pro'][$k]);
                }
            }
        }
        $this->page['pro']['id'] = (string) $this->page['pro']['_id'];
        unset($this->page['pro']['_id'], $this->page['pro']['bundletype'], $this->page['pro']['children']);
        $pro = $this->page['pro'];
        unset($this->page['pro']);
        if (!empty($pro) && !empty($this->page)) {
            $this->page = array_merge($pro, $this->page);
        }
        if (!$this->page) {
            $this->page = array();
        }
        echo json_encode(array('product' => $this->page));
    }

    function productlist() {
        $this->load->model('product_model');
        $this->load->model('productcart_model');
        $this->load->model('category_model');
        $this->load->model('dropdown_model');
        $this->load->model('collection_model');
        $this->load->library('pagination');
        $data = $this->rewrite_post();
        $condition = array();
        if (isset($data['product_type']) && $data['product_type'] != NULL) {
            $type = $this->category_model->getInfoByName($data['product_type']);
            $condition['type'] = (int) $type['_id'];
        }

        if (isset($data['tag']) && $data['tag'] != NULL) {
            $condition['tag.Tag3'] = $data['tag'];
        }
        if (isset($data['collection']) && $data['collection'] != NULL) {
            $data['collection'] = str_replace("+", " ", $data['collection']);
            $col = $this->collection_model->listData($this->country, array('title' => $data['collection']), array('allow' => 1));
            $cids = [];
            foreach ($col as $vo) {
                $cids = $vo['allow'];
            }
            $condition['_id'] = array(
                '$in' => $cids
            );
        }
        if (isset($data['price']) && $data['price'] != NULL) {
            $arr = explode('-', $data['price']);
            $condition['price'] = array('$gt' => $arr[0] * 100, '$lt' => $arr[1] * 100);
        }
        if (isset($data['search']) && $data['search'] != NULL) {
            $condition['$or'] = array(
                array('title' => new MongoRegex('/' . htmlspecialchars($data['search'], ENT_COMPAT) . '/i')),
                array('sku' => new MongoRegex('/' . htmlspecialchars($data['search'], ENT_COMPAT) . '/i'))
            );
        }
        if (isset($data['creator']) && $data['creator'] != NULL) {
            $condition['creator'] = $data['creator'];
        }
        if (isset($data['sortBy']) && $data['sortBy'] != NULL) {
            switch ($data['sortBy']) {
                case 1:$order = '_id,desc';
                    break;
                case 2:$order = '_id,asc';
                    break;
                case 3:$order = 'sold.number,desc';
                    break;
                case 4:$order = 'price,desc';
                    break;
                case 5:$order = 'price,asc';
                    break;
            }
        } else {
            $order = '_id,desc';
        }
        if (isset($data['page'])) {
            $page = (int) $data['page'];
        } else {
            $page = 1;
        }

        $this->page['count'] = $this->product_model->count($this->country, $condition);
        $this->page['pnum'] = ceil($this->page['count'] / $this->per);
        if ($page < 1) {
            $page = 1;
        } else if ($page > $this->page['pnum']) {
            $page = $this->page['pnum'];
        }
        $this->page['page'] = $page;
        $list = $this->product_model->order($order)->limit(($page - 1) * $this->per . ',' . $this->per)->select($this->country, $condition);
        /*
          如果该条件有数据
         */
        if ($list) {
            $lists = array();
            foreach ($list as $key => $vo) {
                $type = $this->category_model->getInfoByID($vo['type']);
                $lists[$key]['product_type'] = $type['title'];
                $lists[$key]['title'] = $vo['title'];
                $lists[$key]['sku'] = $vo['sku'];
                $lists[$key]['creator'] = $vo['creator'];
                $lists[$key]['status'] = $vo['status'] == 1 ? 2 : ($vo['status'] == 2 ? 1 : 3);
                $lists[$key]['id'] = (string) $vo['_id'];
                $img = IMAGE_DOMAIN . '/product/' . $vo['sku'] . '/' . $vo['sku'] . '.jpg';
                if (!@fopen($img, 'r')) {
                    $img = IMAGE_DOMAIN . $vo['image'];
                }
                $lists[$key]['image'] = $img;
            }
            $array = $pids = array_column($lists, 'id');
            foreach ($array as $vo) {
                $key = array_search($vo, $pids);
                $this->page['productlist'][] = $lists[$key];
            }
        }
        unset($list);
        $this->page['prodouct_type_list'] = iterator_to_array($this->dropdown_model->category());
        $this->page['tag'] = $this->dropdown_model->tag();
        $this->page['collection'] = iterator_to_array($this->dropdown_model->collection($this->country));
        $this->page['creator'] = $this->dropdown_model->user();
        if (!empty($this->page['creator'])) {
            foreach ($this->page['creator'] as $key => $value) {
                $this->page['creator'][$key] = (array) $value;
            }
        }
        unset($this->page['template']);
        echo json_encode(array('product' => $this->page));
    }

    function addcollection() {
        $data = $this->rewrite_post();
        if (empty($data['title'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['titleEmpty'])));
        }
        if (empty($data['model'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['conditionsEmpty'])));
        }
        $country_codes = array();
        foreach ($data as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $country_codes = array_merge_recursive($country_codes, $data[$key]);
            }
        }
        if (empty($country_codes)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countryEmpty'])));
        }
        $countrylist = array_keys($this->_category['countryList']);
        $country_codes = array_intersect($country_codes, $countrylist);
        if (empty($country_codes)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countryError'])));
        }
        //条件模式，获取条件
        $conditions = array();
        if ($data['model'] == 2) {
            $this->load->model('category_model');
            $docs = $this->category_model->listData(array(), array('_id' => 1));
            if ($docs) {
                $docs = array_values(iterator_to_array($docs));
                foreach ($docs as $k => $v) {
                    $docs[$k] = $v['_id'];
                }
            } else {
                $docs = array();
            }
            $this->load->model('dropdown_model');
            $tag3s = $this->dropdown_model->tag();
            $tmp = array();
            if ($tag3s) {
                foreach ($tag3s as $key => $value) {
                    $tmp[] = $value['_id'];
                }
            }
            for ($i = 0; $i < count($data['fields']); $i++) {
                if (!empty($data['fields'][$i]) && !empty($data['link'][$i]) && !empty($data['values'][$i])) {

                    if (!in_array($data['link'][$i], array('equals', 'contains'))) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['contidionLinkError'])));
                    }
                    if (!in_array($data['fields'][$i], array('type', 'tag'))) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['contidionFieldsError'])));
                    }
                    if ($data['fields'][$i] == 'type' && !in_array($data['values'][$i], $docs)) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['contidionValuesError'])));
                    }
                    if ($data['fields'][$i] == 'tag' && !in_array($data['values'][$i], $tmp)) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['contidionValuesError'])));
                    }
                    $conditions[] = array('fields' => $data['fields'][$i] == 'type' ? 'type' : 'tag.Tag3', 'link' => $data['link'][$i], 'values' => $data['values'][$i]);
                }
            }
        }
        $seo_url = $data['seo_url'] ? $data['seo_url'] : '';
        if ($seo_url) {
            $seo_url = str_replace(array('&', '#', '%', '"', '?', '/', '\'', '\\', ' '), array('', '', '', '', '', '', '', '', '-'), $seo_url);
            $seo_url = trim($seo_url, '-');
            $s_url = preg_replace("/\-+/", "-", $seo_url);
        } else {
            $s_url = '';
        }
        if (!$s_url) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['seourlEmpty'])));
        }
        $col = !in_array((int) $data['columns'], array(2, 3)) ? 2 : (int) $data['columns'];
        $description = $this->replachtmlimage($data['description']);
        $description2 = $this->replachtmlimage($data['description2']);
        $newlast = !in_array((int) $data['newlast'], array(0, 1)) ? 0 : (int) $data['newlast'];
        $doc = array(
            'title' => htmlspecialchars((string) $data['title'], ENT_COMPAT),
            'description' => htmlspecialchars($description, ENT_COMPAT),
            'description2' => htmlspecialchars($description2, ENT_COMPAT),
            'model' => (int) $data['model'],
            'relation' => (string) $data['relation'],
            'conditions' => $conditions,
            'status' => (int) $data['status'],
            "show_comment" => (int) $data["show_comment"],
            'seo_url' => (string) $s_url,
            'seo_title' => $data['seo_title'] ? htmlspecialchars((string) $data['seo_title'], ENT_COMPAT) : htmlspecialchars((string) $data['title'], ENT_COMPAT),
            'seo_description' => $data['seo_description'] ? (string) $data['seo_description'] : '',
            'seo_keyword' => $data['seo_keyword'] ? (string) $data['seo_keyword'] : '',
            'sort' => 'create_time,-1',
            'allow' => array(),
            'disallow' => array(),
            'creator' => $this->userAccount,
            'create_time' => time(),
            'columns' => $col,
            'newlast' => $newlast
        );
        $this->load->model('collection_model');
        foreach ($country_codes as $k1 => $v1) {
            $hasExists = $this->collection_model->count($v1, array('seo_url' => (string) $s_url));
            if ($hasExists) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['seourlExists'])));
            }
        }
        if ($_id = $this->collection_model->insert($country_codes, $doc, true)) {
            exit(json_encode(array('status' => 200, 'info' => $_id)));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function updatecollection() {
        $data = $this->rewrite_post();
        $collection_id = $data['id'];
        if (empty($collection_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['collectionidError'])));
        }
        if (empty($data['title'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['titleEmpty'])));
        }
        if (empty($data['model'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['conditionsEmpty'])));
        }
        $this->load->model('collection_model');
        $this->load->model('dropdown_model');
        $this->load->model('category_model');
        if (is_string($this->country)) {
            $this->country = explode(',', $this->country);
        }
        foreach ($this->country as $k => $country) {
            //获取Collection的信息，判断是否存在
            $collectionInfo = $this->collection_model->getInfoById($country, $collection_id);
            if (!$collectionInfo) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['collectionNotExists'])));
            }
            $hasExists = $this->collection_model->hasExists($country, (string) $s_url, $collection_id);
            if ($hasExists) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['seourlExists'])));
            }
        }
        foreach ($this->country as $key => $country) {
            //条件模式，获取条件
            $conditions = array();
            if ($data['model'] == 2) {
                $docs = $this->category_model->listData(array(), array('_id' => 1));
                if ($docs) {
                    $docs = array_values(iterator_to_array($docs));
                    foreach ($docs as $k => $v) {
                        $docs[$k] = $v['_id'];
                    }
                } else {
                    $docs = array();
                }
                $tag3s = $this->dropdown_model->tag();
                $tmp = array();
                if ($tag3s) {
                    foreach ($tag3s as $key => $value) {
                        $tmp[] = $value['_id'];
                    }
                }
                for ($i = 0; $i < count($data['fields']); $i++) {
                    if (!empty($data['fields'][$i]) && !empty($data['link'][$i]) && !empty($data['values'][$i])) {
                        if ($data['fields'][$i] == 'tag') {
                            $data['fields'][$i] = 'tag.Tag3';
                        }
                        if (!in_array($data['link'][$i], array('equals', 'contains'))) {
                            exit(json_encode(array('status' => 400, 'info' => $this->error['contidionLinkError'])));
                        }
                        if (!in_array($data['fields'][$i], array('type', 'tag.Tag3'))) {
                            exit(json_encode(array('status' => 400, 'info' => $this->error['contidionFieldsError'])));
                        }
                        if ($data['fields'][$i] == 'type' && !in_array($data['values'][$i], $docs)) {
                            exit(json_encode(array('status' => 400, 'info' => $this->error['contidionValuesError'])));
                        }
                        if ($data['fields'][$i] == 'tag.Tag3' && !in_array($data['values'][$i], $tmp)) {
                            exit(json_encode(array('status' => 400, 'info' => $this->error['contidionValuesError'])));
                        }
                        $conditions[] = array('fields' => $data['fields'][$i], 'link' => $data['link'][$i], 'values' => $data['values'][$i]);
                    }
                }
            }
            $seo_url = $data['seo_url'] ? $data['seo_url'] : '';
            if ($seo_url) {
                $seo_url = str_replace(array('&', '#', '%', '"', '?', '/', '\'', '\\', ' '), array('', '', '', '', '', '', '', '', '-'), $seo_url);
                $seo_url = trim($seo_url, '-');
                $s_url = preg_replace("/\-+/", "-", $seo_url);
            } else {
                $s_url = '';
            }
            if (!$s_url) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['seourlEmpty'])));
            }
            $col = !in_array((int) $data['columns'], array(2, 3)) ? 2 : (int) $data['columns'];
            $description = $this->replachtmlimage($data['description']);
            $description2 = $this->replachtmlimage($data['description2']);
            $newlast = !in_array((int) $data['newlast'], array(0, 1)) ? 0 : (int) $data['newlast'];
            $doc = array(
                'title' => htmlspecialchars((string) $data['title'], ENT_COMPAT),
                'description' => htmlspecialchars($description, ENT_COMPAT),
                'description2' => htmlspecialchars($description2, ENT_COMPAT),
                'model' => (int) $data['model'],
                'relation' => (string) $data['relation'],
                'conditions' => $conditions,
                'status' => (int) $data['status'],
                "show_comment" => (int) $data["show_comment"],
                'seo_url' => (string) $s_url,
                'seo_title' => $data['seo_title'] ? htmlspecialchars((string) $data['seo_title'], ENT_COMPAT) : htmlspecialchars((string) $data['title'], ENT_COMPAT),
                'seo_description' => $data['seo_description'] ? (string) $data['seo_description'] : '',
                'seo_keyword' => $data['seo_keyword'] ? (string) $data['seo_keyword'] : '',
                //'creator' => $this->userAccount,
                //'create_time' => time(),
                'columns' => $col,
                'newlast' => $newlast
            );
            if ($this->collection_model->update($country, $collection_id, $doc)) {
                exit(json_encode(array('status' => 200, 'info' => '')));
            }
        }
    }

    function deletecollection() {
        $data = $this->rewrite_post();
        $collection_id = $data['id'];
        if (empty($collection_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['collectionidError'])));
        }
        //获取需要删除的国家
        $delCountry = array();
        foreach ($data as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $delCountry = array_merge_recursive($delCountry, $data[$key]);
            }
        }
        if (empty($delCountry)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countryEmpty'])));
        }
        $countrylist = array_keys($this->_category['countryList']);
        $delCountry = array_intersect($delCountry, $countrylist);
        if (empty($delCountry)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countryError'])));
        }
        $this->load->model('collection_model');
        if ($this->collection_model->del($delCountry, $collection_id)) {
            exit(json_encode(array('status' => 200, 'info' => '')));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function editcollection() {
        $collection_id = $this->rewrite_post('id');
        if (empty($collection_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['collectionidError'])));
        }

        $this->load->model('collection_model');
        //获取Colletcion信息
        $doc = $this->collection_model->getInfoById($this->country, $collection_id);
        $this->load->model('dropdown_model');
        $this->page['tag3s'] = $this->dropdown_model->tag(array(), $this->country);
        $this->page['categories'] = $this->dropdown_model->category(array(), $this->country);
        if (!$doc) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['collectionNotExists'])));
        }
        if (empty($doc['allow'])) {
            $doc['allow'] = array();
        }
        $productMongo = $this->mongo->{$this->country . '_product'};
        if ($doc['model'] == 1) {//手动模式，查找白名单里的商品信息
            $doc['sortProductID'] = $doc['allow'];
            $mongoCondtion = array(
                '_id' => array('$in' => $doc['allow'])
            );
            if ($doc['sort'] == 'manual') {
                $productInfos = $productMongo->find($mongoCondtion, array('title' => true, 'image' => TRUE));
                $doc['allow'] = iterator_to_array($productInfos);
            } else {
                $sortArr = explode(',', $doc['sort']);
                $sort = array($sortArr[0] => (int) $sortArr[1]);
                $doc['allow'] = iterator_to_array($productMongo->find($mongoCondtion, array('title' => true, 'image' => TRUE))->sort($sort));
            }
        } else {//条件模式
            //拼接条件，生成条件
            if (count($doc['conditions']) == 1) {
                $mongoCondtion = array($doc['conditions'][0]['fields'] => $doc['conditions'][0]['values']);
            } else {
                $mongoCondtion = [];
                foreach ($doc['conditions'] as $condition) {
                    if (!empty($condition['field']) && !empty($condition['link']) && !empty($condition['values'])) {
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
                }
                if ($doc['relation'] == 'or') {
                    $mongoCondtion = array('$or' => $mongoCondtion);
                }
            }
            //手动排序，查找白名单里的商品信息
            if ($doc['sort'] == 'manual') {
                $mongoCondtionAllow = array(
                    '_id' => array('$in' => $doc['allow'])
                );
                $allowProduct = iterator_to_array($productMongo->find($mongoCondtionAllow, array('title' => true, 'image' => TRUE)));
                $searchProduct = iterator_to_array($productMongo->find($mongoCondtion, array('title' => true, 'image' => TRUE)));
                $same = array_intersect_key($allowProduct, $searchProduct); //交集
                $diff = array_diff_key($searchProduct, $same); //差集
                $doc['allow'] = array_merge($same, $diff);
            } else {
                $sortArr = explode(',', $doc['sort']);
                $sort = array($sortArr[0] => (int) $sortArr[1]);
                $doc['allow'] = iterator_to_array($productMongo->find($mongoCondtion, array('title' => true, 'image' => TRUE))->sort($sort));
            }
        }
        $this->page['doc'] = $doc;
        unset($this->page['template']);
        echo json_encode(array('collection' => $this->page));
    }

    function collectionlist() {
        $per_page = $this->per;
        $whereData['$or'] = array(array('status' => array('$lt' => '3')), array('status' => array('$lt' => 3)));
        $data = $this->rewrite_post();
        if ($data) {
            $keyword = $data['title'] ? $data['title'] : 'ALL';
            $this->page['page'] = $data['page'];
        } else {
            $this->page['page'] = 1;
            $keyword = '';
        }

        if ($keyword != '' and $keyword != 'ALL') {
            $whereData['title'] = new MongoRegex("/{$keyword}/i");
        }

        $this->load->model('dropdown_model');
        $this->page['product_type_list'] = array_values(iterator_to_array($this->dropdown_model->category()));
        $this->load->model('collection_model');
        $this->page['count'] = $this->collection_model->count($this->country, $whereData);
        if ($this->page['page'] > $this->page['count'])
            $this->page['page'] = $this->page['count'];
        if ((int) $this->page['page'] < 1)
            $this->page['page'] = 1;
        $this->page['pnum'] = ceil($this->page['count'] / $per_page);
        $collectionArr = $this->collection_model->listData($this->country, $whereData, array(), ($this->page['page'] - 1) * $per_page, $per_page);
        $this->load->model('collectioncountry_model');
        $doc = array();
        foreach ($collectionArr as $collection) {
            $tmp = $this->collectioncountry_model->getCountries($collection['_id']);
            $collections['country_show'] = $tmp['show'];
            $collections['country_hide'] = $tmp['hide'];
            $collections['id'] = $collection['_id'];
            $collections['title'] = $collection['title'];
            $collections['conditions'] = $collection['conditions'];
            $collections['model'] = $collection['model'];
            $collections['status'] = $collection['status'];
            $collections['creator'] = $collection['creator'];
            $doc[] = $collections;
        }
        $this->page['collectlist'] = $doc;
        $this->load->model('language_model');
        $language = $this->language_model->listData();
        $this->load->model('country_model');
        foreach ($language as $key => $language_code) {
            $countries[$key] = $this->country_model->getCountryByLangCode($key);
        }
        $this->page['language'] = $language;
        $this->page['country'] = $countries;
        $this->page['where'] = $keyword == 'ALL' ? '' : $keyword;
        unset($this->page['template']);
        echo json_encode(array('collection' => $this->page));
    }

    function addcountdown() {
        $data = $this->rewrite_post();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if (isset($this->field_map[$k])) {
                    $data[$this->field_map[$k]] = $v;
                }
            }
        }
        if (empty($data['name'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['nameEmpty'])));
        }
        $start = strtotime($data['start'] . ' ' . $data['startTime']);
        $end = strtotime($data['end'] . ' ' . $data['endTime']);
        if ($start === false || $start == -1) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['startTimeError'])));
        }
        if ($end === false || $end == -1) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['endTimeError'])));
        }
        if ($end < $start) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['startendTimeError'])));
        }
        if (!in_array($data['type'], array(1, 2))) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['discountTypeError'])));
        }
        $time = time();
        $doc = array(
            'name' => (string) $data['name'],
            'start' => $start,
            'end' => $end,
            'auto_recount' => $data['auto_recount'] ? 2 : 1,
            'price' => $data['type'] == 2 ? $data['credits'] * 100 : 0,
            'rate' => $data['type'] == 1 ? $data['credits'] : 0,
            'decimal' => $data['saveDecimal'] ? $data['decimal'] : -1,
            'creator' => $this->session->userdata('user_name'),
            'create_time' => $time,
            'update_time' => $time,
            'status' => (int) $data['status']
        );
        $doc['cycle'] = $doc['end'] - $doc['start'];
        $this->load->model('countdown_model');
        if ($id = $this->countdown_model->insert($doc)) {
            exit(json_encode(array('status' => 200, 'info' => $id)));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function updatecountdown() {
        $data = $this->rewrite_post();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if (isset($this->field_map[$k])) {
                    $data[$this->field_map[$k]] = $v;
                }
            }
        }
        $countdown_id = $data['id'];
        if (empty($countdown_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countdownidError'])));
        }
        if (empty($data['name'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['nameEmpty'])));
        }
        $start = strtotime($data['start'] . ' ' . $data['startTime']);
        $end = strtotime($data['end'] . ' ' . $data['endTime']);
        if ($start === false || $start == -1) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['startTimeError'])));
        }
        if ($end === false || $end == -1) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['endTimeError'])));
        }
        if ($end < $start) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['startendTimeError'])));
        }
        if (!in_array($data['type'], array(1, 2))) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['discountTypeError'])));
        }
        $this->load->model('countdown_model');
        $countdown_info = $this->countdown_model->getInfoById($countdown_id);
        if ($countdown_info['status'] != 1) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['startNotEdit'])));
        }
        $time = time();
        $doc = array(
            'name' => (string) $data['name'],
            'start' => $start,
            'end' => $end,
            'auto_recount' => $data['auto_recount'] ? 2 : 1,
            'price' => $data['type'] == 2 ? $data['credits'] * 100 : 0,
            'rate' => $data['type'] == 1 ? $data['credits'] : 0,
            'decimal' => $data['saveDecimal'] ? $data['decimal'] : -1,
            'creator' => $this->session->userdata('user_name'),
            'create_time' => $time,
            'update_time' => $time,
            'status' => (int) $data['status']
        );
        $doc['cycle'] = $doc['end'] - $doc['start'];
        if ($this->countdown_model->update($countdown_id, $doc)) {
            exit(json_encode(array('status' => 200, 'info' => '')));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function deletecountdown() {
        $countdown_id = $this->rewrite_post('id');
        if (empty($countdown_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countdownidError'])));
        }
        $this->load->model('countdown_model');
        $countdown_info = $this->countdown_model->getInfoById($countdown_id);
        if ($countdown_info['status'] != 1) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['startNotEdit'])));
        }
        if ($this->countdown_model->delete(array('id' => $countdown_id, 'status' => 1))) {
            exit(json_encode(array('status' => 200, 'info' => '')));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function editcountdown() {
        $countdown_id = $this->rewrite_post('id');
        if (empty($countdown_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countdownidError'])));
        }
        $this->load->model('countdown_model');
        $countdownInfo = $this->countdown_model->getInfoById($countdown_id);
        if (empty($countdownInfo)) {
            $countdownInfo = array();
        }
        echo json_encode(array('countdown' => $countdownInfo));
    }

    function countdownlist() {
        $per_page = $this->per;
        $data = $this->rewrite_post();
        if ($data) {
            $this->page['page'] = (int) $data['page'];
            $keyword = $data['name'] ? $data['name'] : 'ALL';
        } else {
            $this->page['page'] = 1;
            $keyword = '';
        }

        if ($keyword != '' and $keyword != 'ALL') {
            $whereData['name like'] = "%$keyword%";
        } else {
            $whereData = array();
        }
        $this->load->model('countdown_model');
        $this->page['count'] = $this->countdown_model->count($whereData);
        if ($this->page['page'] > $this->page['count']) {
            $this->page['page'] = $this->page['count'];
        }
        if ($this->page['page'] < 1) {
            $this->page['page'] = 1;
        }
        $this->page['pnum'] = ceil($this->page['count'] / $this->per);
        $fields = 'id,name,start,end,auto_recount,price,rate,decimal,creator,create_time,update_time,status';
        $this->load->model('countdown_model');
        $this->page['countdownList'] = $this->countdown_model->listData($whereData, 'update_time', 'desc', ($this->page['page'] - 1) * $per_page, $per_page, $fields);
        $this->page['where'] = $keyword == 'ALL' ? '' : $keyword;
        uset($this->page['template']);
        echo json_encode(array('countdownlist' => $this->page));
    }

    function addproductype() {
        $type = $this->rewrite_post('title');
        if (empty($type)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['titleEmpty'])));
        }
        $doc = array(
            '_id' => time(),
            'title' => $type
        );
        $this->load->model('category_model');
        $c = $this->category_model->getInfoByName($type);
        if ($c) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['typeExists'])));
        }
        $result = $this->category_model->insert($doc);
        if ($result['ok']) {
            exit(json_encode(array('status' => 200, 'info' => $doc['_id'])));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function updateproductpe() {
        $data = $this->rewrite_post();
        $type = $data['title'];
        if (empty($type)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['titleEmpty'])));
        }
        $doc = array(
            'title' => $type
        );
        $category_id = $data['id'];
        if (empty($category_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['_idError'])));
        }
        $this->load->model('category_model');

        $result = $this->category_model->update($category_id, $doc);
        if ($result['ok']) {
            exit(json_encode(array('status' => 200, 'info' => '')));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function deleteproductype() {
        $category_id = $this->rewrite_post('id');
        if (empty($category_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['_idError'])));
        }
        $this->load->model('category_model');
        $result = $this->category_model->remove($category_id);
        if ($result['ok']) {
            exit(json_encode(array('status' => 200, 'info' => '')));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function productypelist() {
        $this->load->model('category_model');
        $whereData = array();
        $docs = $this->category_model->listData($whereData);
        if ($docs) {
            $docs = array_values(iterator_to_array($docs));
            foreach ($docs as $k => $v) {
                $docs[$k]['id'] = $v['_id'];
                unset($docs[$k]['_id']);
            }
        }
        echo json_encode(array('producttype' => $docs));
    }

    function productypeselect() {
        $this->load->model('category_model');
        $whereData = array();
        $docs = $this->category_model->listData($whereData, array('_id' => 1, 'title' => 1));
        if ($docs) {
            $docs = array_values(iterator_to_array($docs));
            foreach ($docs as $k => $v) {
                $docs[$k]['id'] = $v['_id'];
                unset($docs[$k]['_id']);
            }
        }
        echo json_encode(array('producttypeselect' => $docs));
    }

    function collectionselect() {
        $this->load->model('collection_model');
        $collectionArr = $this->collection_model->listData($this->country, array(), array('_id' => 1, 'title' => 1), 0, 'ALL');
        if ($collectionArr) {
            $collectionArr = array_values(iterator_to_array($collectionArr));
            foreach ($collectionArr as $k => $v) {
                $collectionArr[$k]['id'] = $v['_id'];
                unset($collectionArr[$k]['_id']);
            }
        }
        echo json_encode(array('collectionselect' => $collectionArr));
    }

    function countdownselect() {
        $this->load->model('countdown_model');
        $countdownList = $this->countdown_model->listData(array('status' => 2), 'update_time', 'desc', 0, 'ALL', 'id,name');
        echo json_encode(array('countdownselect' => $countdownList));
    }

    function collctiontagselect() {
        $this->load->model('dropdown_model');
        $tag3s = $this->dropdown_model->tag();
        $tmp = array();
        if ($tag3s) {
            foreach ($tag3s as $key => $value) {
                $tmp[] = array('id' => $value['_id'], 'name' => $value['title']);
            }
        }
        echo json_encode(array('collctiontagselect' => $tmp));
    }

    //查询记录是否存在
    function getrecord() {
        $data = $this->rewrite_post();
        $type = $data['type'];
        $_id = $data['id'];
        if (empty($_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['idError'])));
        }
        if (!in_array($type, array('product', 'collection', 'countdown'))) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['typeError'])));
        }
        if ($type == 'product') {
            $this->load->model('product_model');
            $rs = $this->product_model->findOne($this->country, $_id);
        } elseif ($type == 'collection') {
            $this->load->model('collection_model');
            $rs = $this->collection_model->getInfoById($this->country, $_id);
        } elseif ($type == 'countdown') {
            $this->load->model('countdown_model');
            $rs = $this->countdown_model->getInfoById($_id);
        }
        if ($rs) {
            exit(json_encode(array('status' => 200, 'info' => true)));
        } else {
            exit(json_encode(array('status' => 200, 'info' => false)));
        }
    }

    function changecountdownstatus() {
        $data = $this->rewrite_post();
        $countdown_id = $data['id'];
        if (empty($countdown_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countdownidError'])));
        }
        $status = $data['status'] == 2 ? 2 : 1;
        $this->load->model('countdown_model');
        if ($this->countdown_model->changeStatus(array('id' => $countdown_id), array('status' => $status))) {
            exit(json_encode(array('status' => 200, 'info' => $countdown_id)));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
        }
    }

    function syncCollection() {
        $data = $this->rewrite_post();
        $collection_id = $data['id'];
        if (empty($collection_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['collectionidError'])));
        }
        if (!$data['country']) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['selectSourceCountry'])));
        }
        $sourceCountry_code = $data['country'];

        //获取需要同步的国家
        $syncCountry = [];
        foreach ($data as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $syncCountry = array_merge_recursive($syncCountry, $data[$key]);
            }
        }
        if (!count($syncCountry)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['syncCountry'])));
        }
        $countrylist = array_keys($this->_category['countryList']);
        $syncCountry = array_intersect($syncCountry, $countrylist);
        if (empty($syncCountry)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countryError'])));
        }
        $this->load->model('collection_model');
        //获取源国家Collection的信息
        $collectionInfo = $this->collection_model->getInfoById($sourceCountry_code, $collection_id);
        if ($collectionInfo) {
            //获取已同步的国家
            $this->load->model('collectioncountry_model');
            $tmp = $this->collectioncountry_model->getCountries($collection_id);
            $syncCountry_have = array_unique(array_merge($tmp['show'], $tmp['hide']));

            unset($collectionInfo['_id']);
            if ($this->collection_model->sync($syncCountry_have, $syncCountry, $collection_id, $collectionInfo, true)) {
                exit(json_encode(array('status' => 200, 'info' => $collection_id)));
            } else {
                exit(json_encode(array('status' => 400, 'info' => $this->error['dbError'])));
            }
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['collectionError'])));
        }
    }

    function changeproductstatus() {
        $data = $this->rewrite_post();
        $product_id = $data['id'];
        if (empty($product_id)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['productidError'])));
        }
        $status = (int) $data['status'];
        if (!in_array($status, array(1, 2, 3))) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['productStatusError'])));
        }
        $status = $status == 1 ? 2 : ($status == 2 ? 1 : 3);
        $country = [];
        foreach ($data as $key => $value) {
            if (substr_count($key, 'lang') > 0) {
                $c = $data[$key];
                if (!is_array($c)) {
                    $c = explode(',', $c);
                }
                $country = array_merge_recursive($country, $c);
            }
        }
        if (!count($country)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countryError'])));
        }
        $countrylist = array_keys($this->_category['countryList']);
        $country = array_intersect($country, $countrylist);
        if (empty($country)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['countryError'])));
        }
        $this->load->model('product_model');
        foreach ($country as $k => $v) {
            $this->product_model->updateMainPro($v, array('_id' => new MongoId($product_id)), array('$set' => array('status' => new MongoInt64($status))));
        }
        exit(json_encode(array('status' => 200, 'info' => $product_id)));
    }

    function localupdateproduct() {
        $datas = $this->rewrite_post();
        $data = $datas['product'];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                if (isset($this->field_map[$k])) {
                    $data[$this->field_map[$k]] = $v;
                    unset($data[$k]);
                }
            }
        }
        if (isset($data['status']) && !in_array($data['status'], array(1, 2, 3))) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['productStatusError'])));
        } elseif (isset($data['status'])) {
            $data['status'] = $data['status'] == 1 ? 2 : ($data['status'] == 2 ? 1 : 3);
        }
        if (isset($data['freebies']) && $data['freebies'] > 0) {
            $data['freebies'] = 1;
        } elseif (isset($data['freebies'])) {
            $data['freebies'] = 0;
        }
        if (isset($data['GF_enable']) && $data['GF_enable'] > 0) {
            $data['GF_enable'] = 1;
            if (empty($data['GF_color'])) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedcolorEmpty'])));
            }
            if (empty($data['GF_size'])) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedsizeEmpty'])));
            }
            if (!in_array($data['GF_gender'], array('male', 'female', 'unisex'))) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedgenderError'])));
            }
            if (!in_array($data['GF_agegroup'], array('newborn', 'infant', 'toddler', 'kids', 'adult'))) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['googlefeedagegroupError'])));
            }
        } elseif (isset($data['GF_enable'])) {
            $data['GF_enable'] = 0;
        }
        if (isset($data['seo']['title']) && empty($data['seo']['title'])) {
            $data['seo']['title'] = $data['title'];
        }
        if (isset($data['seo_url']) && empty($data['seo_url'])) {
            $data['seo_url'] = $data['title'];
        }
        if (isset($data['seo_url'])) {
            $data['seo_url'] = str_replace(array('&', '#', '%', '"', '?', '/', '\'', '\\', ' '), array('', '', '', '', '', '', '', '', '-'), $data['seo_url']);
            $data['seo_url'] = trim($data['seo_url'], '-');
            $data['seo_url'] = preg_replace("/\-+/", "-", $data['seo_url']);
        }
        if (isset($data['creator']))
            unset($data['creator']);
        if (isset($data['description'])) {
            $data['description'] = $this->replachtmlimage($data['description']);
        }
        if (isset($data['specification'])) {
            $data['specification'] = $this->replachtmlimage($data['specification']);
        }
        if (isset($data['topreview'])) {
            $data['topreview'] = $this->replachtmlimage($data['topreview']);
        }
        if (isset($data['relativeproduct'])) {
            if (!is_array($data['relativeproduct']) && !empty($data['relativeproduct'])) {
                $data['relativeproduct'] = explode(',', $data['relativeproduct']);
            } elseif (empty($data['relativeproduct'])) {
                $data['relativeproduct'] = array();
            }
        }
        // 价格区间
        $tag = array(
            '0' => '$0 - $9.99',
            '1' => '$10 - $19.99',
            '2' => '$20 - $29.99',
            '3' => '$30 - $39.99',
            '4' => '$40 - $69.99',
            '5' => '$70 - $99.99',
            '6' => '$100 - $199.99',
            '7' => '$200+',
        );
        // 载入模型
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('collection_model');
        $this->load->model('countdown_model');
        $this->load->model('tag_model');
        $this->load->model('dropdown_model');
        // Title及Description转义
        $strip_tags_desc = htmlspecialchars(substr(strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"), ' ', $data['description']))), 0, 160));
        if (isset($data['title']))
            $data['title'] = htmlspecialchars($data['title'], ENT_COMPAT);
        if (isset($data['description']))
            $data['description'] = htmlspecialchars($data['description'], ENT_COMPAT);
        if (isset($data['specification']))
            $data['specification'] = htmlspecialchars($data['specification'], ENT_COMPAT);
        if (isset($data['topreview']))
            $data['topreview'] = htmlspecialchars($data['topreview'], ENT_COMPAT);
        if (isset($data['seo']['title']))
            $data['seo']['title'] = isset($data['seo']['title']) ? htmlspecialchars($data['seo']['title'], ENT_COMPAT) : '';
        if (isset($data['seo']['description']))
            $data['seo']['description'] = (isset($data['seo']['description']) && !empty($data['seo']['description'])) ? htmlspecialchars(substr(strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"), ' ', $data['seo']['description']))), 0, 160)) : $strip_tags_desc;
        if (isset($data['seo']['keyword']))
            $data['seo']['keyword'] = isset($data['seo']['keyword']) ? htmlspecialchars($data['seo']['keyword'], ENT_COMPAT) : '';
        if (isset($data['shopping_feed']))
            $data['shopping_feed'] = htmlspecialchars($data['shopping_feed'], ENT_COMPAT);
        if (isset($data['title']) && empty($data['title'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['titleEmpty'])));
        }
        if (isset($data['sku']) && $data['sku'] == NULL) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['skuEmpty'])));
        }
        if (isset($data['cost']) && empty($data['cost'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['costEmpty'])));
        }
        if (isset($data['type']) && empty($data['type'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['typeEmpty'])));
        }
        $countries = is_string($this->country) ? explode(',', $this->country) : $this->country;
        if (isset($data['sku']) || isset($data['seo_url'])) {
            foreach ($countries as $country) {
                $hasExists = $this->product_model->hasExists($country, $data['_id'], $data['sku'], $data['seo_url']);
                if ($hasExists) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['skuorseourlExists'])));
                }
            }
        }
        if ((isset($data['pice']) && floatval($data['price']) <= 0) || (isset($data['original']) && floatval($data['original']) <= 0) || (isset($data['cost']) && floatval($data['cost']) <= 0)) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['priceError'])));
        }
        if ((isset($data['price']) || isset($data['original'])) && floatval($data['price']) > floatval($data['original'])) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['priceltError'])));
        }
        if (isset($data['weight']) && (int) $data['weight'] <= 0) {
            exit(json_encode(array('status' => 400, 'info' => $this->error['weightError'])));
        }
        if (isset($data['collection']) && !empty($data['collection'])) {
            foreach ($countries as $country) {
                $collection_id_array = iterator_to_array($this->dropdown_model->collection($country, array('_id' => 1)));
                $tmp = array();
                if ($collection_id_array) {
                    foreach ($collection_id_array as $k1 => $v1) {
                        $tmp[] = $v1['_id'];
                    }
                }
                if (empty($tmp)) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['pleaseSetCollection'])));
                } else {
                    if (!is_array($data['collection'])) {
                        $data['collection'] = explode(',', $data['collection']);
                    }
                    $int = array_intersect($data['collection'], $tmp);
                    if (empty($int)) {
                        exit(json_encode(array('status' => 400, 'info' => $this->error['collectionError'])));
                    } else {
                        $data['collection'] = join(',', $int);
                    }
                }
            }
        } elseif (isset($data['collection']) && empty($data['collection'])) {
            $data['collection'] = '';
        }
        if (isset($data['countdown']) && !empty($data['countdown'])) {
            $countdown = $this->countdown_model->getInfoById($data['countdown']);
            if (!$countdown || (!empty($countdown) && $countdown['status'] == 1)) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['countdownError'])));
            }
        }
        if (isset($data['type'])) {
            $category = $this->category_model->getInfoById($data['type']);
            if (!$category) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['typeError'])));
            }
        }
        if ((isset($data['image']) && !empty($data['image'])) || (isset($data['thumb'][0]) && !empty($data['thumb'][0]))) {
            $root = dirname(dirname(dirname((__DIR__))));
            $url = $root . '/uploads/product/' . $data['sku'];
            if (!file_exists($url)) {
                mkdir($url, 0777, true);
            }
        }
        if (isset($data['image']) && !empty($data['image'])) {
            if (is_array($data['image'])) {
                $image = $data['image'];
            } else {
                $image = explode(',', $data['image']);
            }
            $this->product_model->product_image_queue($url, $image);
            if (!empty($image)) {
                foreach ($image as $k => $v) {
                    $data['pics'][$k]['img'] = '/product/' . $data['sku'] . '/' . basename($v);
                    $data['pics'][$k]['sort'] = $k + 1;
                }
            }
            $data['image'] = '/product/' . $data['sku'] . '/' . basename($image[0]);
        } elseif (isset($data['image'])) {
            $data['pics'] = array();
            $data['image'] = '';
        }
        if (isset($data['thumb'][0]) && !empty($data['thumb'][0])) {
            $this->product_model->product_image_queue($url, $data['thumb'], $data['sku']);
        }
        // 主表价格
        if (isset($data['cost']))
            $data['cost']*=100;
        if (isset($data['price'])) {
            $data['price']*=100;
            // 定义最低价，后面如果有子属性会覆盖
            $price = $data['price'];
        }
        if (isset($data['original']) && $data['original'] > 0) {
            $data['original']*=100;
        }
        if (isset($data['bundle']) && $data['bundle'] > 0) {
            $data['bundle']*=100;
        }
        if (isset($data['variants'])) {
            $variants = array();
            $i = 0;
            foreach ($data['variants'] as $key => $vo) {
                if (isset($vo['value']) && !is_array($vo['value'])) {
                    $value = explode(',', $vo['value']);
                    $vCount = count($value);
                } elseif (isset($vo['value'])) {
                    $vCount = count($vo['value']);
                }
                if (isset($vo['value_map']) && !is_array($vo['value_map'])) {
                    $value_map = explode(',', $vo['value_map']);
                    $mCount = count($value_map);
                } elseif (isset($vo['value_map'])) {
                    $mCount = count($vo['value_map']);
                }
                if ((isset($vCount) && isset($mCount)) && $vCount != $mCount) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['optionsvalueError'])));
                }
                if (isset($vo['value']) && is_array($vo['value'])) {
                    $vo['value'] = join(',', $vo['value']);
                }
                if (isset($vo['value']) && (strpos($vo['value'], '/') !== false || strpos($vo['value'], '"') !== false)) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['optionsvalueCharsError'])));
                }
                if (isset($vo['value_map']) && is_array($vo['value_map'])) {
                    $vo['value_map'] = join(',', $vo['value_map']);
                }
                if (is_array($vo['option'])) {
                    $vo['option'] = $vo['option'][0];
                }
                if (is_array($vo['option_map'])) {
                    $vo['option_map'] = $vo['option_map'][0];
                }
                if (isset($vo)) {
                    $variants[$i] = $vo;
                    $i++;
                }
            }
            if ($i > 0)
                $data['variants'] = $variants;
        }
        if (isset($data['details'])) {
            $details = array();
            $j = 0;
            foreach ($data['details'] as $key => $vo) {
                if ((isset($vo['pice']) && floatval($vo['price']) <= 0) || (isset($vo['original']) && floatval($vo['original']) <= 0) || (isset($vo['cost']) && floatval($vo['cost']) <= 0)) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['priceError'])));
                }
                if ((isset($vo['price']) || isset($vo['original'])) && floatval($vo['price']) > floatval($vo['original'])) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['priceltError'])));
                }
                if (isset($vo['weight']) && (int) $vo['weight'] <= 0) {
                    exit(json_encode(array('status' => 400, 'info' => $this->error['weightError'])));
                }
                if (isset($vo['cost']))
                    $vo['cost'] *= 100;
                if (isset($vo['price']))
                    $vo['price'] *= 100;
                if (isset($vo['original']) && $vo['original'] > 0) {
                    $vo['original'] *= 100;
                }
                if (isset($vo['bundle']) && $vo['bundle'] > 0) {
                    $vo['bundle']*=100;
                }
                if (isset($vo['status'])) {
                    $vo['status'] = $vo['status'] == 1 ? 2 : ($vo['status'] == 2 ? 1 : 3);
                }
                if (!empty($vo)) {
                    $details[$j] = $vo;
                    $j++;
                }
            }
            if ($j > 0)
                $data['details'] = $details;
            if ($i > 0 && $j > 0) {
                $data['children'] = 1;
                // 用子属性里最低价格覆盖price变量
                //array_multisort($details, SORT_ASC);
                $details = arr_sort($details, 'price', 'asc');
                $data['price'] = $price = $details[0]['price'];
                $data['original'] = $details[0]['original'];
                $data['bundle'] = $details[0]['bundle'];
            }
        }
        if (isset($price)) {
            // 用最低价格计算tag1标签
            if ($price >= 0 && $price <= 999) {
                $data['tag']['Tag1'] = $tag[0];
            } else if ($price >= 1000 && $price <= 1999) {
                $data['tag']['Tag1'] = $tag[1];
            } else if ($price >= 2000 && $price <= 2999) {
                $data['tag']['Tag1'] = $tag[2];
            } else if ($price >= 3000 && $price <= 3999) {
                $data['tag']['Tag1'] = $tag[3];
            } else if ($price >= 4000 && $price <= 6999) {
                $data['tag']['Tag1'] = $tag[4];
            } else if ($price >= 7000 && $price <= 9999) {
                $data['tag']['Tag1'] = $tag[5];
            } else if ($price >= 10000 && $price <= 19999) {
                $data['tag']['Tag1'] = $tag[6];
            } else {
                $data['tag']['Tag1'] = $tag[7];
            }
        }
        foreach ($countries as $country) {
            $old = $this->product_model->findOne($country, $data['_id']);
            if (empty($old)) {
                exit(json_encode(array('status' => 400, 'info' => $this->error['productnotexists'])));
            }
            if (isset($data['sold']) && isset($data['sold']['init']))
                $soldtotal[$country] = $data['sold']['init'] + $old['sold']['number'];
            $soldnumber[$country] = $old['sold']['number'];
            $olds['tag'][$country] = $old['tag'];
        }
        // 组装B\C模式售价
        if ($data['bnumber'] != NULL && $data['bprice'] != NULL) {
            $bnumber = explode(',', $data['bnumber']);
            $bprice = explode(',', $data['bprice']);
            $i = 0;
            foreach ($bnumber as $key => $vo) {
                $data['plural'][$i]['number'] = $bnumber[$key];
                $data['plural'][$i]['price'] = $bprice[$key] * 100;
                $i++;
            }
            if ($i > 0) {
                $data['bundletype'] = 1;
            }
        }
        // Tag1由价格自行决定，不再支持修改
        if (isset($data['tag']['Tag2']) && !empty($data['tag']['Tag2'])) {
            if (!is_array($data['tag']['Tag2'])) {
                $data['tag']['Tag2'] = explode(',', $data['tag']['Tag2']);
            }
            $data['tag']['Tag2'] = array_unique($data['tag']['Tag2']);
        } elseif (isset($data['tag']['Tag2'])) {
            $data['tag']['Tag2'] = array();
        }
        if (isset($data['tag']['Tag3']) && !empty($data['tag']['Tag3'])) {
            if (!is_array($data['tag']['Tag3'])) {
                $data['tag']['Tag3'] = explode(',', $data['tag']['Tag3']);
            }
            $data['tag']['Tag3'] = array_unique($data['tag']['Tag3']);
        } elseif (isset($data['tag']['Tag3'])) {
            $data['tag']['Tag3'] = array();
        }
        //$this->db->trans_start();
        foreach ($countries as $country) {
            if (isset($data['tag']) && !empty($data['tag'])) {
                $rstag = $this->tag_model->upTag($country, $olds['tag'][$country], $data['tag']);
            }
            if (isset($data['collection'])) {
                $this->collection_model->addOneProduct($data['_id'], $country, $data['collection']);
            }
            if (isset($data['countdown']) && !$data['countdown']) {
                $this->countdown_model->clearOneProduct($data['_id'], $country);
            } elseif (isset($data['countdown'])) {
                $this->countdown_model->addOneProduct($data['_id'], $country, $data['countdown']);
            }
            if (isset($data)) {
                $data['sold']['total'] = $soldtotal[$country];
                $data['sold']['number'] = $soldnumber[$country];
                $this->product_model->update($country, $data);
            }
        }
        //$this->db->trans_complete();
        exit(json_encode(array('status' => 200, 'info' => '')));
    }

    function productprice() {
        @set_time_limit(0);
        $countrylist = array_keys($this->_category['countryList']);
        if (!empty($countrylist)) {
            $countryprice = array();
            $this->load->model('product_model');
            foreach ($countrylist as $k => $v) {
                $i = 0;
                while ($i > -1) {
                    $rs = $this->product_model->limit($i . ',200')->select($v, array('status' => array('$in' => array(1, 3))));
                    if ($rs) {
                        foreach ($rs as $k1 => $v1) {
                            if (!empty($v1['details'])) {
                                foreach ($v1['details'] as $k2 => $v2) {
                                    $v1['details'][$k2]['price'] = $v2['price'] / 100;
                                    $v1['details'][$k2]['cost'] = $v2['cost'] / 100;
                                    $v1['details'][$k2]['original'] = $v2['original'] / 100;
                                    $v1['details'][$k2]['bundle'] = $v2['bundle'] / 100;
                                }
                            }
                            $countryprice[$v][] = array(
                                'id' => (string) $v1['_id'],
                                'cost' => $v1['cost'] / 100,
                                'price' => $v1['price'] / 100,
                                'gross' => $v1['gross'],
                                'original' => $v1['original'] / 100,
                                'bundle' => $v1['bundle'] / 100,
                                'variants' => $v1['variants'],
                                'details' => $v1['details']
                            );
                        }
                        $i+=200;
                    } else {
                        $i = -1;
                    }
                }
            }
            exit(json_encode(array('status' => 200, 'info' => $countryprice)));
        } else {
            exit(json_encode(array('status' => 400, 'info' => $this->error['notsetcountry'])));
        }
    }

    function replachtmlimage($pageContents = '') {
        if (empty($pageContents)) {
            return '';
        }
        $reg = '/<img +src=[\'"](http.*?)[\'"]/i';
        preg_match_all($reg, $pageContents, $results);
        $find = $results[1];
        $replace = array();
        if (!empty($find)) {
            foreach ($find as $k => $v) {
                $v = str_replace('\\', '', $v);
                $type = getimagesize($v); //取得图片的大小，类型等
                $file_content = base64_encode(file_get_contents($v)); //base64编码
                switch ($type[2]) {//判读图片类型
                    case 1:$img_type = "gif";
                        break;
                    case 2:$img_type = "jpg";
                        break;
                    case 3:$img_type = "png";
                        break;
                }
                $replace[$k] = 'data:image/' . $img_type . ';base64,' . $file_content; //合成图片的base64编码
            }
            return str_replace($find, $replace, $pageContents);
        }
        return $pageContents;
    }

}

?>