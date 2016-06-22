<?php

class excel extends PC_Controller {

    protected $country = 'US';

    public function __construct() {
        parent::__construct();
        //echo md5('pandacheerproduct' . date('YmdH'));
        if ($this->input->get('token') != md5('pandacheerproduct' . date('YmdH'))) {
            exit('参数token是必须的或者不合法，可以向开发人员索取token');
        }
        $this->country = $this->session->userdata('my_country');
    }

    // 从shopify导入！谨慎！防止再次导入覆盖
    public function shopify($i = 1) {
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('tag_model');
        // $json = $this->_getJson('https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/products.json?handle=mens-stylish-long-sleeve-shirt');
        $url = 'https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/products.json?limit=1&published_status=published&page=' . $i;
        echo '本次采集地址:' . $url;
        $json = $this->_getJson($url);
        $data = json_decode($json, true);
        foreach ($data['products'] as $vo) {
            $pro['oid'] = $vo['id'];
            $pro['title'] = $vo['title'];
            $pro['description'] = $vo['body_html'];
            $pro['creator'] = $vo['vendor'];
            // category 暂不写入
            $result = $this->category_model->getInfoByName($vo['product_type']);
            if (isset($result['_id'])) {
                $pro['type'] = (string) $result['_id'];
            } else {
                $_id = time();
                $array['_id'] = new MongoInt32($_id);
                $array['title'] = $vo['product_type'];
                $this->category_model->insert($array);
                $pro['type'] = (string) $_id;
            }
            $pro['create_time'] = strtotime($vo['created_at']);
            $pro['seo_url'] = $vo['handle'];
            $pro['update_time'] = strtotime($vo['updated_at']);
            // 分解TAG标签，价格存入Tag1，其他存入Tag2
            $tag = $vo['tags'];
            $tags = explode(', ', $tag);
            foreach ($tags as $vi) {
                $pattern = '/[$]\d+/';
                preg_match($pattern, $vi, $matches, PREG_OFFSET_CAPTURE);
                if (isset($matches[0])) {
                    $pro['tag']['Tag1'] = $vi;
                } elseif (!in_array($vi, $pro['tag']['Tag2'])) {
                    $pro['tag']['Tag2'][] = $vi;
                }
            }
            // 暂时不写入Tag表
            // $this->tag_model->addTag($this->country,$pro['Tag']);
            // Variants
            $j = 0;
            foreach ($vo['options'] as $key => $vj) {
                $pro['variants'][$key]['option'] = $pro['variants'][$key]['option_map'] = $vj['name'];
                $pro['variants'][$key]['value'] = $pro['variants'][$key]['value_map'] = implode(',', $vj['values']);
                $j++;
            }
            $pro['sku'] = $vo['variants'][0]['sku'];
            // 是否存在Variants
            if ($j > 0) {
                $pro['children'] = 1;
            } else {
                $pro['children'] = 0;
            }
            // 替换
            $change = array(' / ' => '/');
            foreach ($vo['variants'] as $key => $vk) {
                $pro['details'][$key]['stock'] = 0;
                $pro['details'][$key]['sku'] = $vk['sku'] . '/' . strtr($vk['title'], $change);
                $pro['details'][$key]['status'] = 1;
                $pro['details'][$key]['price'] = 0;
                $pro['details'][$key]['original'] = 0;
                $pro['details'][$key]['bundle'] = 0;
            }
            $result = $this->product_model->findPro($this->country, array('$or' => array(array('sku' => $vo['variants'][0]['sku']), array('seo_url' => $vo['handle']))));
            if ($result) {
                echo '<p>有同名产品，进行更新...</p>';
                $pro['_id'] = $result['_id'];
                echo $pro['_id'];
                $rs = $this->product_model->update($this->country, $pro);
                if ($rs) {
                    echo '<p>更新成功！</p>';
                    $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                    $this->load->view('excel', $array);
                } else {
                    exit('<p>更新失败！' . $pro['title'] . '</p>');
                }
                // 不更新了，做新增处理
                $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                echo '<p>没有同名产品，进行新增...</p>';
                $pro['_id'] = new MongoId();
                print_r($pro['_id']);
                $rs = $this->product_model->insert($this->country, $pro);
                if ($rs) {
                    echo '<p>新增成功！</p>';
                    $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                    $this->load->view('excel', $array);
                } else {
                    exit('<p>新增失败！' . $pro['title'] . '</p>');
                }
            }
        }
        // echo '<pre>';
        // print_r($data);
    }

    // 导出所有SKU
    public function exportsku($i = 0) {
        $log = FCPATH . 'allSku.txt';
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            echo $vo['_id'] . '<br>';
            if (count($vo['details']) > 0) {
                foreach ($vo['details'] as $vi) {
                    $content = file_get_contents($log);
                    $content .= $vi['sku'] . "\r\n";
                    file_put_contents($log, $content);
                }
                echo '<p>子SKU新增成功！</p>';
                $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                $content = file_get_contents($log);
                $content .= $vo['sku'] . "\r\n";
                file_put_contents($log, $content);
                echo '<p>主SKU新增成功！</p>';
                $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                $this->load->view('excel', $array);
            }
        }
    }

    public function skuchange($i = 0) {
        $log = FCPATH . 'variants.txt';
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            echo '"_id": ObjectId("' . $vo['_id'] . '")';
            echo '<br>';
            echo $vo['sku'];
            echo '<br>';
            if (count($vo['variants']) >= 2) {
                echo count($vo['variants']);
                $content = file_get_contents($log);
                $content .= $vo['_id'] . "\r\n";
                file_put_contents($log, $content);
            }
        }
        $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
        $this->load->view('excel', $array);
    }

    public function sku2price($i = 0) {
        $log = FCPATH . 'sku2price.txt';
        $this->load->model('product_model');
        $this->load->model('shipformula_model');
        $this->load->model('country_model');
        $rate = $this->country_model->getCountryList('au_rate');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        // echo '<pre>';
        foreach ($rs as $vo) {
            // echo $vo['price'];
            // echo '<br>';
            $cost = $vo['cost'];
            $weight = $vo['weight'];
            //AU澳大利亚 CA加拿大 NZ新西兰
            $auship = $this->shipformula_model->calculateShipping('AU', $weight);
            $auprice = ceil(($cost + $auship * 100) / 0.6 / $this->RMBtoAU * $rate['AU']);
            // echo $auprice;
            // echo '<br>';
            $caship = $this->shipformula_model->calculateShipping('CA', $weight);
            $caprice = ceil(($cost + $caship * 100) / 0.6 / $this->RMBtoAU * $rate['CA']);
            // echo $caprice;
            // echo '<br>';
            $nzship = $this->shipformula_model->calculateShipping('NZ', $weight);
            $nzprice = ceil(($cost + $nzship * 100) / 0.6 / $this->RMBtoAU * $rate['NZ']);
            // echo $nzprice;
            // echo '<br>';
            $content = file_get_contents($log);
            $content .= "<tr><td>" . $vo['sku'] . "</td><td>" . ($vo['cost'] / 100) . "</td><td>" . ($auprice / 100) . "</td><td>" . ($caprice / 100) . "</td><td>" . ($nzprice / 100) . "</td></tr>\r\n";
            file_put_contents($log, $content);
            echo '<p>更新完成！</p>';
            $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
            $this->load->view('excel', $array);
        }
    }

    public function toexecl() {
        $log = FCPATH . 'sku2price.txt';
        $content = file_get_contents($log);
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=sku2price.xls");
        echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <title></title>
        <style>
        td{
            text-align:center;
            font-size:12px;
            font-family:Arial, Helvetica, sans-serif;
            border:#1C7A80 1px solid;
            color:#152122;
            width:100px;
        }
        table,tr{
            border-style:none;
        }
        .title{
            color:#FFFFFF;
            font-weight:bold;
            background:#7DDCF0;
        }
        </style>
        </head>
        <body>
        <table width='800' border='1'>
        <tr>
        <td class='title'>SKU</td>
        <td class='title'>RMB</td>
        <td class='title'>AU</td>
        <td class='title'>CA</td>
        <td class='title'>NZ</td>
        </tr>
        " . $content . "
        </table>
        </body>
        </html>
        ";
    }

    public function upseourl($i = 0) {
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            if ($vo['seo_url'] != NULL) {
                $url = $vo['seo_url'];
                $url = str_replace(" ", "-", str_replace("&", "-", str_replace("'", "", $url)));
                $url = str_replace("--", "-", $url);
                $vo['seo_url'] = str_replace("---", "-", $url);
                $result = $this->product_model->update($this->country, $vo);
                if ($result) {
                    echo '<p>成功！下一条！</p>';
                    $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                    $this->load->view('excel', $array);
                } else {
                    echo '<p>写入失败！</p>';
                }
            } else {
                echo '<p>并不存在SEOURL！</p>';
                $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                $this->load->view('excel', $array);
            }
        }
    }

    // 检索重复
    public function repeat($i = 0) {
        @set_time_limit(0);
        // 重复字段：sku seo_url
        $this->load->model('product_model');
        $tmp = array();
        $html = '';
        while ($rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country)) {
            foreach ($rs as $vo) {
                $hasexists = $this->product_model->hasExists($this->country, (string) $vo['_id'], $vo['sku'], $vo['seo_url']);
                if ($hasexists && !in_array($vo['sku'], $tmp)) {
                    $tmp[] = $vo['sku'];
                    $html .= "<tr><td>" . $vo['sku'] . "</td></tr>";
                }
            }
            $i++;
        }
        if ($html) {
            $title = array('产品sku');
            $this->toxls($title, $html);
            echo "导出重复产品到excel完成";
        } else {
            echo "没有重复产品";
        }
    }

    //删除重复产品
    function removerepeat($i = 0) {
        @set_time_limit(0);
        // 重复字段：sku seo_url
        $this->load->model('product_model');
        $tmp = $sku = array();
        while ($rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country)) {
            foreach ($rs as $vo) {
                $hasexists = $this->product_model->hasExists($this->country, (string) $vo['_id'], $vo['sku'], $vo['seo_url']);
                if ($hasexists && !in_array($vo['sku'], $sku)) {
                    $sku[] = $vo['sku'];
                    foreach ($hasexists as $vv) {
                        $tmp[] = (string) $vv['_id'];
                    }
                }
            }
            $i++;
        }
        if (!empty($tmp)) {
            foreach ($tmp as $v) {
                $this->product_model->delete($this->country, $v);
            }
            echo "去除重复产品完成";
        } else {
            echo "没有重复产品";
        }
    }

    public function uppic($i = 0) {
        $log = FCPATH . 'log.txt';
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        // echo '<pre>';
        foreach ($rs as $vo) {
            // print_r($vo);
            echo $vo['_id'];
            if ($vo['image'] != NULL && count($vo['pics']) >= 1) {
                echo '<p>有图！不管它！</p>';
                $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                echo '<p>没图！搞起！</p>';
                $temp = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/temp/' . $vo['sku'] . '/';
                echo $temp;
                echo '<br>';
                $dir = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/product/' . $vo['sku'] . '/';
                echo $dir;
                if (file_exists($temp)) {
                    $files = scandir($temp);
                    if (count($files) > 2) {
                        // 满足数据库无图，产品有图的条件
                        // echo $vo['sku'];
                        // 先创建文件夹
                        if (!file_exists($dir)) {
                            mkdir($dir);
                        }
                        foreach ($files as $key => $vi) {
                            // 排除掉.和..
                            if ($key > 1) {
                                $vo['pics'][]['img'] = '/product/' . $vo['sku'] . '/' . $vi;
                                $return = copy($temp . $vi, $dir . $vi);
                            }
                        }
                        $vo['image'] = $vo['pics'][0]['img'];
                        print_r($vo);
                        $rs = $this->product_model->update($this->country, $vo);
                        if ($rs) {
                            echo '<p>写入成功！完成更新！</p>';
                            $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                            $this->load->view('excel', $array);
                        } else {
                            echo '<p>写入失败！请检查' . $vo['_id'] . '</p>';
                        }
                    } else {
                        echo '<p>没图，有文件夹，但是里面没有图片文件！</p>';
                        $content = file_get_contents($log);
                        $content .= $vo['sku'] . "没图，有文件夹，但是里面没有图片文件！\r\n";
                        file_put_contents($log, $content);
                        $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                        $this->load->view('excel', $array);
                    }
                } else {
                    echo '<p>没图，也找不到对应的文件夹！</p>';
                    $content = file_get_contents($log);
                    $content .= $vo['sku'] . "没图，也找不到对应的文件夹！\r\n";
                    file_put_contents($log, $content);
                    $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                    $this->load->view('excel', $array);
                }
            }
        }
    }

    //批量更新价格
    function updateprice($i = 0) {
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
        $this->load->model('product_model');
        $this->load->model('country_model');
        $rate = $this->country_model->getCountryList('au_rate');
        $rs = $this->product_model->order('_id,desc')->limit($i . ',1')->select($this->country);
        if ($rs) {
            foreach ($rs as $vo) {
                $_price = $vo['price'];
                $_original = $vo['original'];
                $_bundle = $vo['bundle'];
                $bili = ''; //*(老元人民币汇率/新人民币汇率/老汇率*新汇率)
                $price = ceil($_price * $bili);
                $tmp = array($price);
                $original = ceil($_original * $bili);
                $bundle = ceil($_bundle * $bili);
                $vo['price'] = $price;
                $vo['original'] = $original;
                $vo['bundle'] = $bundle;
                if (count($vo['variants']) <= 0) {
                    $vo['details'] = array();
                } else {
                    if (count($vo['details']) > 0) {
                        foreach ($vo['details'] as $key => $vi) {
                            if ($vo['details'][$key]['price'] > 0) {
                                $d_price = ceil($vo['details'][$key]['price'] * $bili);
                            } else {
                                $d_price = '';
                            }
                            if ($vo['details'][$key]['original'] > 0) {
                                $d_original = ceil($vo['details'][$key]['original'] * $bili);
                            } else {
                                $d_original = $original;
                            }
                            if ($vo['details'][$key]['bundle'] > 0) {
                                $d_bundle = ceil($vo['details'][$key]['bundle'] * $bili);
                            } else {
                                $d_bundle = $bundle;
                            }
                            $tmp[] = $d_price;
                            $vo['details'][$key]['price'] = $d_price;
                            $vo['details'][$key]['original'] = $d_original;
                            $vo['details'][$key]['bundle'] = $d_bundle;
                        }
                    }
                }
                $minprice = min($tmp);
                if ($minprice >= 0 && $minprice <= 999) {
                    $vo['tag']['Tag1'] = $tag[0];
                } else if ($minprice >= 1000 && $minprice <= 1999) {
                    $vo['tag']['Tag1'] = $tag[1];
                } else if ($minprice >= 2000 && $minprice <= 2999) {
                    $vo['tag']['Tag1'] = $tag[2];
                } else if ($minprice >= 3000 && $minprice <= 3999) {
                    $vo['tag']['Tag1'] = $tag[3];
                } else if ($minprice >= 4000 && $minprice <= 6999) {
                    $vo['tag']['Tag1'] = $tag[4];
                } else if ($minprice >= 7000 && $minprice <= 9999) {
                    $vo['tag']['Tag1'] = $tag[5];
                } else if ($minprice >= 10000 && $minprice <= 19999) {
                    $vo['tag']['Tag1'] = $tag[6];
                } else {
                    $vo['tag']['Tag1'] = $tag[7];
                }
                $result = $this->product_model->update($this->country, $vo);
                if ($result) {
                    echo '<p>更新成功！</p>';
                    $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                    $this->load->view('excel', $array);
                    exit;
                } else {
                    exit('<p>更新失败！' . $pro['title'] . '</p>');
                }
            }
        } else {
            echo '<p>所有产品更新完成！</p>';
        }
    }

    //初始化价格快速方法
    public function quickinitprice($_id = '') {
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
        $this->load->model('product_model');
        $this->load->model('shipformula_model');
        $this->load->model('country_model');
        $rate = $this->country_model->getCountryList('au_rate');
        $where = array();
        if($_id)$_id = (string)$_id;
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where = array('_id'=>array('$in'=>$allowpro['allow']));
            }
        }
        $count = $this->product_model->count($this->country,$where);
        if (strlen($_id) == 24) {
            $rs = $this->product_model->order('_id,asc')->select($this->country, array('_id' => new MongoId($_id)));
        } else {
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        $log = FCPATH . $this->country . 'quickinitprice.txt';
        if ($rs) {
            foreach ($rs as $vo) {
                $cost = $vo['cost'];
                $weight = $vo['weight'];
                if(isset($vo['gross'])){
                    $gross = round(1 - $vo['gross'] / 100, 1);
                    if ($gross <= 0 || $gross >= 1) {
                        $gross = 0.6;
                        $vo['gross'] = 40;
                    }
                }else{
                    $gross = 0.6;
                    $vo['gross'] = 40;
                }
                $ship = $this->shipformula_model->calculateShipping($this->country, $weight);
                $price = ceil(($cost + $ship * 100) / $gross / $this->RMBtoAU * $rate[$this->country]);
                $price = (intval($price/100)+0.99)*100;
                if ($price >= 0 && $price <= 999) {
                    $vo['tag']['Tag1'] = $tag[0];
                } else if ($price >= 1000 && $price <= 1999) {
                    $vo['tag']['Tag1'] = $tag[1];
                } else if ($price >= 2000 && $price <= 2999) {
                    $vo['tag']['Tag1'] = $tag[2];
                } else if ($price >= 3000 && $price <= 3999) {
                    $vo['tag']['Tag1'] = $tag[3];
                } else if ($price >= 4000 && $price <= 6999) {
                    $vo['tag']['Tag1'] = $tag[4];
                } else if ($price >= 7000 && $price <= 9999) {
                    $vo['tag']['Tag1'] = $tag[5];
                } else if ($price >= 10000 && $price <= 19999) {
                    $vo['tag']['Tag1'] = $tag[6];
                } else {
                    $vo['tag']['Tag1'] = $tag[7];
                }
                // 虚假售价 220%
                $original = ceil($price * 2.2);
                // 默认捆绑售价 85%
                $bundle = ceil($price * 0.85);
                $vo['cost'] = $cost;
                $vo['price'] = $price;
                $vo['original'] = $original;
                $vo['bundle'] = $bundle;
                if (count($vo['variants']) <= 0) {
                    $vo['details'] = array();
                } else {
                    if (count($vo['details']) > 0) {
                        foreach ($vo['details'] as $key => $vi) {
                            $cost = (isset($vi['cost'])&&$vi['cost'])?$vi['cost']:$vo['cost'];
                            $weight = (isset($vi['weight'])&&$vi['weight']>0)?$vi['weight']:$vo['weight'];
                            $ship = $this->shipformula_model->calculateShipping($this->country, $weight);
                            if(isset($vi['gross'])){
                                $gross = round(1 - $vi['gross'] / 100, 1);
                                if ($gross <= 0 || $gross >= 1) {
                                    $gross = 0.6;
                                    $vi['gross'] = 40;
                                }
                            }elseif(isset($vo['gross'])){
                                $gross = round(1 - $vo['gross'] / 100, 1);
                                if ($gross <= 0 || $gross >= 1) {
                                    $gross = 0.6;
                                    $vi['gross'] = 40;
                                }else{
                                    $vi['gross'] = $vo['gross'];
                                }
                            }else{
                                $gross = 0.6;
                                $vi['gross'] = 40;
                            }
                            $price = ceil(($cost + $ship * 100) / $gross / $this->RMBtoAU * $rate[$this->country]);
                            $price = (intval($price/100)+0.99)*100;
                            // 虚假售价 220%
                            $original = ceil($price * 2.2);
                            // 默认捆绑售价 85%
                            $bundle = ceil($price * 0.85);
                            $vo['details'][$key]['price'] = $price;
                            $vo['details'][$key]['original'] = $original;
                            $vo['details'][$key]['bundle'] = $bundle;
                            $vo['details'][$key]['cost'] = $cost;
                            $vo['details'][$key]['weight'] = $weight;
                        }
                        $details = $vo['details'];
                        $details=arr_sort($details,'price','asc');
                        $vo['price'] =$price= $details[0]['price'];
                        if ($price >= 0 && $price <= 999) {
                            $vo['tag']['Tag1'] = $tag[0];
                        } else if ($price >= 1000 && $price <= 1999) {
                            $vo['tag']['Tag1'] = $tag[1];
                        } else if ($price >= 2000 && $price <= 2999) {
                            $vo['tag']['Tag1'] = $tag[2];
                        } else if ($price >= 3000 && $price <= 3999) {
                            $vo['tag']['Tag1'] = $tag[3];
                        } else if ($price >= 4000 && $price <= 6999) {
                            $vo['tag']['Tag1'] = $tag[4];
                        } else if ($price >= 7000 && $price <= 9999) {
                            $vo['tag']['Tag1'] = $tag[5];
                        } else if ($price >= 10000 && $price <= 19999) {
                            $vo['tag']['Tag1'] = $tag[6];
                        } else {
                            $vo['tag']['Tag1'] = $tag[7];
                        }
                    }
                }
                $result = $this->product_model->update($this->country, $vo);
                if (!$result) {
                    file_put_contents($log, $pro['title'] . PHP_EOL, FILE_APPEND);
                }
            }
            echo "<p>更新完成</p>";
        } else {
            echo '<p>没有产品！</p>';
        }
    }

    public function bundletype($i = 0) {
        $this->load->model('product_model');
        // $this->load->model('category_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            if (isset($vo['bundletype'])) {
                // 有Bundletype
                if (count($vo['plural']) == 0) {
                    $vo['bundletype'] = 0;
                } else {
                    $vo['bundletype'] = 1;
                }
                $rs = $this->product_model->update($this->country, $vo);
                if ($rs) {
                    echo '<p>' . (string) $vo['_id'] . '更新成功！</p>';
                    $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                    $this->load->view('excel', $array);
                } else {
                    echo '<p>更新失败！</p>';
                }
            } else {
                // 没有Bundletype
                if (count($vo['plural']) == 0) {
                    $vo['bundletype'] = 0;
                } else {
                    $vo['bundletype'] = 1;
                }
                $rs = $this->product_model->update($this->country, $vo);
                if ($rs) {
                    echo '<p>' . (string) $vo['_id'] . '更新成功！</p>';
                    $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
                    $this->load->view('excel', $array);
                } else {
                    echo '<p>更新失败！</p>';
                }
            }
        }
    }

    public function add($i = 0) {
        $log = FCPATH . 'log.txt';

        $this->load->model('product_model');
        $url = 'https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/products.json?limit=1&published_status=published&page=' . $i;
        // $url = 'https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/products/398339600.json';
        echo '本次采集地址:' . $url;
        $json = $this->_getJson($url);
        $data = json_decode($json, true);
        echo '<pre>';
        foreach ($data['products'] as $vo) {
            echo '<p>' . $vo['published_at'] . '</p>';
            if ($vo['published_at'] != NULL) {
                // SKU是否存在
                $sku = $vo['variants'][0]['sku'];
                $condition = array(
                    'sku' => $sku
                );
                $rs = $this->product_model->findPro($this->country, $condition);
                if (isset($rs['_id'])) {
                    // 有产品
                    echo '<p>' . $sku . '已经存在！</p>';
                    $array['goto'] = '/excel/add/' . ($i + 1);
                    $this->load->view('excel', $array);
                } else {
                    // 无产品
                    $content = file_get_contents($log);
                    $content .= $sku . "\r\n";
                    file_put_contents($log, $content);
                    echo '<p>' . $sku . '不存在！记录！</p>';
                    $array['goto'] = '/excel/add/' . ($i + 1);
                    $this->load->view('excel', $array);
                }
            } else {
                echo '<p>' . $sku . '属性为Hidden！</p>';
                $array['goto'] = '/excel/add/' . ($i + 1);
                $this->load->view('excel', $array);
            }
        }
    }

    public function nopic($i = 0) {
        $log = FCPATH . 'log.txt';
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            if ($vo['status'] != 2) {
                $j = 0;
                foreach ($vo['pics'] as $vi) {
                    if ($vi['img'] != NULL) {
                        $j++;
                    }
                }
                if ($j <= 0) {
                    $content = file_get_contents($log);
                    $content .= $vo['sku'] . "缺少图片\r\n";
                    file_put_contents($log, $content);
                    echo '<p>' . $vo['sku'] . '缺少图片！</p>';
                    $array['goto'] = '/excel/nopic/' . ($i + 1);
                    $this->load->view('excel', $array);
                } else {
                    echo '<p>' . $vo['sku'] . '正常！</p>';
                    $array['goto'] = '/excel/nopic/' . ($i + 1);
                    $this->load->view('excel', $array);
                }
            } else {
                echo '<p>' . $vo['sku'] . '隐藏，跳过！</p>';
                $array['goto'] = '/excel/nopic/' . ($i + 1);
                $this->load->view('excel', $array);
            }
        }
    }

    public function showpro($i = 0) {
        $this->load->model('product_model');
        $this->load->model('category_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            $url = 'https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/products/' . $vo['oid'] . '.json';
            $json = $this->_getJson($url);
            $data = json_decode($json, true);
            $result = $this->category_model->getInfoByName($data['product']['product_type']);
            if (isset($result['_id'])) {
                $vo['type'] = (string) $result['_id'];
            } else {
                $_id = time();
                $array['_id'] = new MongoInt32($_id);
                $array['title'] = $data['product']['product_type'];
                $this->category_model->insert($array);
                $vo['type'] = $_id;
            }
            $return = $this->product_model->update($this->country, $vo);
            if ($return) {
                echo '<p>新增成功！</p>';
                $array['goto'] = '/excel/showpro/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                echo '<p>新增失败！</p>';
            }
        }
    }

    public function tag($i = 0) {
        $this->load->model('product_model');
        $this->load->model('tag_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            $url = 'https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/products/' . $vo['oid'] . '.json';
            $json = $this->_getJson($url);
            $data = json_decode($json, true);

            $tag = $data['product']['tags'];
            $tags = explode(', ', $tag);
            foreach ($tags as $vi) {
                if (strstr($vi, '$')) {
                    $vo['tag']['Tag1'] = $vi;
                } elseif (!in_array($vi, $vo['tag']['Tag2'])) {
                    $vo['tag']['Tag2'][] = $vi;
                }
            }
            $this->tag_model->addTag($this->country, $vo['tag']);
            $return = $this->product_model->update($this->country, $vo);
            if ($return) {
                echo '<p>新增成功！</p>';
                $array['goto'] = '/excel/tag/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                echo '<p>新增失败！</p>';
            }
        }
    }

    public function searchBundle($i = 0) {
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            if ($vo['status'] == 2) {
                // Delete
                $result = $this->product_model->delete($this->country, $vo['_id']);
                if ($result) {
                    echo '<p>本条信息是Bundle，已经删除！</p>';
                    $array['goto'] = '/excel/searchBundle/' . ($i + 1);
                    $this->load->view('excel', $array);
                }
            } else {
                foreach ($vo['details'] as $vi) {
                    if (stristr($vi['sku'], 'bundle')) {
                        // Delete
                        $result = $this->product_model->delete($this->country, $vo['_id']);
                        if ($result) {
                            echo '<p>本条信息是Bundle，已经删除！</p>';
                            $array['goto'] = '/excel/searchBundle/' . ($i + 1);
                            $this->load->view('excel', $array);
                        }
                    }
                }
                // Next
                echo '<p>本条信息没问题，准备检测下一条！</p>';
                $array['goto'] = '/excel/searchBundle/' . ($i + 1);
                $this->load->view('excel', $array);
            }
        }
    }

    public function diff($i = 0) {
        $log = FCPATH . 'log.txt';

        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->ex_select($this->country);
        $result = $this->product_model->deff($this->country, $rs['_id']);
        if ($result) {
            echo '<p>' . $rs['sku'] . '没有被删除！</p>';
            $array['goto'] = '/excel/diff/' . ($i + 1);
            $this->load->view('excel', $array);
        } else {
            echo '<p>' . $rs['sku'] . '已被删除！</p>';
            $content = file_get_contents($log);
            $content .= (string) $rs['sku'] . "\r\n";
            file_put_contents($log, $content);
            $array['goto'] = '/excel/diff/' . ($i + 1);
            $this->load->view('excel', $array);
        }
    }

    public function collect($i = 0) {
        // https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/collects.json?limit=1
        // https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/custom_collections.json?since_id=23452901
        // https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/collects.json?collection_id=23452901
        $url = 'https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/custom_collections.json?since_id=23452901';
        echo '本次采集地址:' . $url;
        $json = $this->_getJson($url);
        $data = json_decode($json, true);
        echo '<pre>';
        print_r($data);

        // $this->load->model('product_model');
        // $rs = $this->product_model->order('_id,asc')->limit($i.',1')->select($this->country);
        // foreach($rs as $vo){
        // echo $vo['oid'];
        // }
    }

    public function renew($i = 0) {
        $log = FCPATH . 'log.txt';
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        if ($rs === true) {
            echo '<p>OK！写入成功！</p>';
            $array['goto'] = '/excel/renew/' . ($i + 1);
            $this->load->view('excel', $array);
        } else {
            echo '<p>NO！写入失败！</p>';
            $content = file_get_contents($log);
            $content .= $rs . "在新表中不存在\r\n";
            file_put_contents($log, $content);
            $array['goto'] = '/excel/renew/' . ($i + 1);
            $this->load->view('excel', $array);
        }
    }

    public function price($i = 0) {
        /*
          $this->load->model('product_model');
          $rs = $this->product_model->order('_id,asc')->limit($i.',1')->select($this->country);
          // echo '<pre>';
          foreach($rs as $vo){
          $vo['bundletype'] = 0;
          $result = $this->product_model->up($this->country,$vo);
          }
          // $result = $this->product_model->up($this->country,$data);
          if($result === true){
          echo '<p>OK！写入成功！</p>';
          $array['goto'] = '/excel/price/'.($i+1);
          $this->load->view('excel',$array);
          }else{
          echo '<p>NO！写入失败！</p>';
          $array['goto'] = '/excel/price/'.($i+1);
          $this->load->view('excel',$array);
          }
         */
        // 准备好记录文件
        $log = FCPATH . 'log.txt';
        if (!file_exists($log)) {
            file_put_contents($log, '');
        }
        // 载入模型
        $this->load->model('product_model');
        $this->load->model('shipformula_model');
        // 读取信息
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        $rate = $this->country_model->getCountryList('au_rate');

        foreach ($rs as $vo) {
            if ($vo['cost'] != NULL || $vo['weight'] != NULL) {
                $cost = $vo['cost'];
                $weight = $vo['weight'];
                $ship = $this->shipformula_model->calculateShipping($this->country, $weight);
                // 40%毛利率 = / 0.6
                $vo['price'] = ceil(($cost + $ship) / 0.6 / $this->RMBtoAU * $rate[$this->country]);
                // 虚假售价 220%
                $vo['original'] = ceil($vo['price'] * 2.2);
                // 默认捆绑售价 85%
                $vo['bundle'] = ceil($vo['price'] * 0.85);
                $result = $this->product_model->update($this->country, $vo);
                if ($result) {
                    echo '<p>' . $vo['sku'] . '更新成功！</p>';
                    $array['goto'] = '/excel/price/' . ($i + 1);
                    $this->load->view('excel', $array);
                } else {
                    echo '<p>' . $vo['cost'] . '更新失败！</p>';
                }
            }
        }
        /*
          foreach($rs as $vo){
          echo $vo['_id'];
          if($vo['cost']==NULL||$vo['weight']==NULL){
          $content = file_get_contents($log);
          $content .= $vo['sku']."的产品成本价或重量为空！\r\n";
          file_put_contents($log,$content);
          $array['goto'] = '/excel/price/'.($i+1);
          $this->load->view('excel',$array);
          }else{
          // if($vo['cost'] > 100){
          $vo['cost'] = $vo['cost'] * 100;
          // }
          $result = $this->product_model->update($this->country,$vo);
          if($result){
          echo '<p>'.$vo['cost'].'</p>';
          $array['goto'] = '/excel/price/'.($i+1);
          $this->load->view('excel',$array);
          }else{
          echo '<p>什么鬼？</p>';
          }
          }
          }
         */
    }

    public function priceDetails($i = 0) {
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        echo '<pre>';
        foreach ($rs as $vo) {
            // 图片
            if (isset($vo['pics'][0]['img']) && $vo['pics'][0]['img'] != NULL) {
                $vo['image'] = $vo['pics'][0]['img'];
            }
            // 子属性价格
            if (count($vo['details']) > 0) {
                foreach ($vo['details'] as $key => $vi) {
                    $vo['details'][$key]['price'] = $vo['price'];
                    $vo['details'][$key]['original'] = $vo['original'];
                    $vo['details'][$key]['bundle'] = $vo['bundle'];
                }
            }
            $result = $this->product_model->update($this->country, $vo);
            if ($result) {
                echo '<p>' . $vo['sku'] . ' 更新成功！</p>';
                $array['goto'] = '/excel/priceDetails/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                echo '<p>更新失败！</p>';
            }
        }
    }

    public function getPic($i = 0) {
        // 检查log文件
        $log = FCPATH . 'log.txt';
        if (!file_exists($log)) {
            file_put_contents($log, '');
        }
        // 遍历产品数据库
        $this->load->model('product_model');
        $data = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        // print_r($data[0]);
        $sku = $data[0]['sku'];
        echo '<p>本次采集对象：' . $sku . '</p>';
        // 取出产品SKU
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/../uploads/product/' . $sku . '/';
        $file = $dir . $sku . '.jpg';
        echo '<p>' . $file . '</p>';
        if (file_exists($file)) {
            // 有主（长）图
            $data['0']['image'] = '/product/' . $sku . '/' . $sku . '.jpg';
        } else {
            echo '<p>' . $sku . '的产品主（长）图不存在</p>';
            // $content = file_get_contents($log);
            // $content .= $sku."的产品主（长）图不存在\r\n";
            // file_put_contents($log,$content);
        }
        if (file_exists($dir)) {
            // 有（方）图
            $files = scandir($dir);
            echo '<p>' . $dir . '</p>';
            $j = 0;
            foreach ($files as $key => $vo) {
                if ($key > 1) {
                    // 非长图
                    if ($vo != $sku . '.jpg') {
                        $data[0]['pics'][$j]['img'] = '/product/' . $sku . '/' . $vo;
                        $j++;
                    }
                }
            }
            $rs = $this->product_model->update($this->country, $data[0]);
            if ($rs) {
                echo '<p>' . $sku . '的产品（方）图存在！写入成功！</p>';
                $array['goto'] = '/excel/getPic/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                echo '<p>' . $sku . '的产品写入失败！</p>';
            }
        } else {
            echo '<p>' . $sku . '的产品（方）图不存在</p>';
            $content = file_get_contents($log);
            $content .= $sku . "的产品（方）图不存在\r\n";
            file_put_contents($log, $content);
            $array['goto'] = '/excel/getPic/' . ($i + 1);
            $this->load->view('excel', $array);
        }
    }

    public function getMeta($i = 0) {
        // 遍历产品数据库
        $this->load->model('product_model');
        $pro = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        $desc = htmlspecialchars_decode($pro[0]['description']);
        $desc = strip_tags($desc);
        $desc = substr($desc, 0, 236);
        $desc .= ' ...';
        $pro[0]['seo']['description'] = $desc;
        // 取出产品的oid
        $id = $pro[0]['oid'];
        if ($pro[0]['oid'] != NULL) {
            // 根据oid获取Meta信息
            $url = 'https://0c2e9f1d55a3f0afaaa6748c5284efba:9650de5aa396d94a91f84ee4b169efa2@stanchen.myshopify.com/admin/products/' . $id . '/metafields.json';
            echo '本次采集地址:' . $url;
            $json = $this->_getJson($url);
            $data = json_decode($json, true);
            // 更新产品数据库Meta信息
            foreach ($data['metafields'] as $vo) {
                if ($vo['key'] == 'title_tag') {
                    $pro[0]['seo']['title'] = $vo['value'];
                }
            }
        }
        $result = $this->product_model->update($this->country, $pro[0]);
        if ($result) {
            echo '<p>写入成功！</p>';
            $array['goto'] = '/excel/getMeta/' . ($i + 1);
            $this->load->view('excel', $array);
        } else {
            echo '<p>写入失败</p>';
            $array['goto'] = '/excel/getMeta/' . ($i + 1);
            $this->load->view('excel', $array);
        }
    }

    // Gross 统一毛利率
    public function gross($i = 0) {
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            $vo['gross'] = 40;
            $result = $this->product_model->update($this->country, $vo);
            if ($result) {
                echo '<p>写入成功！</p>';
                $array['goto'] = '/excel/gross/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                echo '<p>写入失败</p>';
            }
        }
    }
    
    public function quickgross($gross=40,$_id = '') {
        $where = array();
        if($_id)$_id = (string)$_id;
        $this->load->model('product_model');
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where = array('_id'=>array('$in'=>$allowpro['allow']));
            }
        }
        $count = $this->product_model->count($this->country,$where);
        if (strlen($_id) == 24) {
            $rs = $this->product_model->order('_id,asc')->select($this->country, array('_id' => new MongoId($_id)));
        } else {
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            if((int)$gross<=0||(int)$gross>=100){
                $gross = 40;
            }
            foreach ($rs as $vo) {
                $vo['gross'] = (int)$gross;
                $result = $this->product_model->update($this->country, $vo);
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    public function quickcost($o='',$x='',$_id = '') {
        if(!$o||!$x)exit('参数错误');
        $where = array('cost'=>intval($o*100));
        if(!is_int($where['cost'])){
            exit('参数错误');
        }
        $x = intval($x*100);
        if(!is_int($x)){
            exit('参数错误');
        }
        if($_id)$_id = (string)$_id;
        $this->load->model('product_model');
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where['_id'] = array('$in'=>$allowpro['allow']);
            }
        }
        $count = $this->product_model->count($this->country,$where);
        if (strlen($_id) == 24) {
            $where['_id'] = new MongoId($_id);
            $rs = $this->product_model->order('_id,asc')->select($this->country, $where);
        } else {
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach ($rs as $vo) {
                $vo['cost'] = $x;
                $result = $this->product_model->update($this->country, $vo);
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }

    public function bundle($i = 0) {
        $this->load->model('product_model');
        $rs = $this->product_model->order('_id,asc')->limit($i . ',1')->select($this->country);
        foreach ($rs as $vo) {
            $sku = strtolower($vo['sku']);
            echo $sku . '<br>';
            if (strstr($sku, 'bundle')) {
                echo '发现bundleSKU！';
                $this->product_model->delete($this->country, $vo['_id']);
                $array['goto'] = '/excel/bundle/' . ($i + 1);
                $this->load->view('excel', $array);
            } else {
                echo '正常SKU！';
                $array['goto'] = '/excel/bundle/' . ($i + 1);
                $this->load->view('excel', $array);
            }
        }
    }

    private function _getJson($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($ch);
    }

    function toxls($title, $content) {
        if (!empty($title)) {
            $titles = "<tr>";
            foreach ($title as $v) {
                $titles .= "<td class='title'>" . $v . "</td>";
            }
            $titles .= "</tr>";
        } else {
            $titles = '';
        }
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=" . $this->country . date('YmdHis') . ".xls");
        echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <title></title>
        <style>
        td{
            text-align:center;
            font-size:12px;
            font-family:Arial, Helvetica, sans-serif;
            border:#1C7A80 1px solid;
            color:#152122;
            width:100px;
                    }
        table,tr{
            border-style:none;
                }
        .title{
            color:#FFFFFF;
            font-weight:bold;
            background:#7DDCF0;
                }
        </style>
        </head>
        <body>
        <table width='800' border='1'>" . $titles . $content . "
        </table>
        </body>
        </html>
        ";
            }

    //查询选项含有default title的产品
    function specproduct($country = 'US', $key = 'default title') {
        if (!empty($this->country)) {
            $country = $this->country;
        }
        $this->load->model('product_model');
        $rs = $this->product_model->specproduct($country, $key);
        $html = "";
        if ($rs) {
            foreach ($rs as $v) {
                $u = site_url('product/edit/' . (string) $v['_id']);
                $html .= "<tr><td><a href='" . $u . "'>" . (string) $v['_id'] . "</a></td><td>" . $v['title'] . "</td><td>" . $v['seo_url'] . "</td></tr>";
            }
        }
        $title = array('产品id', '产品title', 'seo_url');
        $this->toxls($title, $html);
    }

    function removespecproductattr($country = 'US', $key = 'default title') {
        if (!empty($this->country)) {
            $country = $this->country;
        }
        $this->load->model('product_model');
        $rs = $this->product_model->removespecproductattr($country, $key);
        echo "over";
    }

    //查询没有图片的产品
    function nopicproduct() {
        @set_time_limit(0);
        $this->load->model('product_model');
        $country = $this->country;
        $rs = $this->product_model->nopicproduct($country);
        $html = "";
        if ($rs) {
            foreach ($rs as $v) {
                $u = site_url('product/edit/' . (string) $v['_id']);
                $html .= "<tr><td><a href='" . $u . "'>" . $v['sku'] . "</a></td></tr>";
            }
        }
        $title = array('产品SKU');
        $this->toxls($title, $html);
    }

    //查询没有图片的产品
    function noshowpicproduct() {
        @set_time_limit(0);
        $this->load->model('product_model');
        $country = $this->country;
        $rs = $this->product_model->noshowpicproduct($country);
        $html = "";
        if ($rs) {
            $t = array();
            foreach ($rs as $v) {
                if (!empty($v['pics'])) {
                    foreach ($v['pics'] as $key => $value) {
                        $imgname = rawurlencode(basename($value['img']));
                        $value['img'] = dirname($value['img']) . '/' . $imgname;
                        if (!in_array($v['sku'], $t) && !@fopen(IMAGE_DOMAIN . $value['img'], 'r')) {
                            array_push($t, $v['sku']);
                            $u = site_url('product/edit/' . (string) $v['_id']);
                            $html .= "<tr><td><a href='" . $u . "'>" . $v['sku'] . "</a></td></tr>";
                        }
                    }
                }
            }
        }
        $title = array('产品SKU');
        $this->toxls($title, $html);
    }

    function nothumbproduct() {
        @set_time_limit(0);
        $this->load->model('product_model');
        $count = $this->product_model->count($this->country);
        $rs = $this->product_model->limit('0,' . $count)->order('_id,asc')->select($this->country);
        if ($rs) {
            $result = array();
            foreach ($rs as $k => $v) {
                $thumb = IMAGE_DOMAIN . '/product/' . $v['sku'] . '/' . rawurlencode($v['sku']) . '.jpg';
                if (!@fopen($thumb, 'r')) {
                    $result[] = $v['sku'];
                }
            }
            if (!empty($result)) {
                $html = join(PHP_EOL, $result);
                $this->tocsv(array('sku'), $html);
            } else {
                echo "没有缺失大图的图片产品";
            }
        } else {
            echo "没有产品";
        }
    }

    function allandmapsku() {
        @set_time_limit(0);
        $this->load->model('product_model');
        $rs = $this->product_model->selectSku($this->country);
        $this->load->model('sku_mapping_model');
        if ($rs) {
            $vv = array();
            foreach ($rs as $v) {
                $t = $this->sku_mapping_model->bySku($v);
                if (!empty($t['erp_sku'])) {
                    $vv['sku'][] = $t['erp_sku'];
                } else {
                    $vv['sku'][] = $v;
                }
            }
            $this->tocsv(array(), join(PHP_EOL, $vv['sku']));
        } else {
            echo "没有产品";
        }
    }

    //查询没有类型的产品
    function notypeproduct() {
        $this->load->model('product_model');
        $rs = $this->product_model->notypeproduct($this->country);
        $html = "";
        if ($rs) {
            foreach ($rs as $v) {
                $u = site_url('product/edit/' . (string) $v['_id']);
                $co = isset($v['collection'])?join(',',$v['collection']):'';
                $html .= "<tr><td><a href='" . $u . "'>" . $v['sku'] . "</a></td><td>".$co."</td></tr>";
            }
        }
        $title = array('产品SKU','collection');
        $this->toxls($title, $html);
    }

    //导出collection
    function exportCollection($type = 'xls') {
        if (!in_array($type, array('xls', 'csv'))) {
            $type = 'xls';
        }
        $this->load->model('collection_model');
        $data = $this->collection_model->listData($this->country, array(), array('seo_url' => 1), 0, 'ALL');
        if ($type == 'xls') {
            $html = "";
            if ($data) {
                foreach ($data as $key => $value) {
                    $u = site_url('collection/loadEditPage/' . (string) $value['_id']);
                    $html .= "<tr><td><a href='" . $u . "'>" . $value['seo_url'] . "</a></td></tr>";
                }
            }
            $title = array('Collection URL');
            $this->toxls($title, $html);
        } else {
            $tmp = [];
            if ($data) {
                foreach ($data as $key => $value) {
                    $tmp[] = $value['seo_url'];
                }
            }
            $tmp = join(PHP_EOL, $tmp);
            $this->tocsv(array('Collection URL'), $tmp);
        }
    }

    //导出产品sku，售价，type
    function exportProduct($field='sku,price,type') {
        @set_time_limit(0);
        $this->load->model('product_model');
        $rs = $this->product_model->getproductSimpleField($this->country,$field);
        $html = "";
        $title = is_array($field)?$field:  explode(',', $field);
        if ($rs) {
            foreach ($rs as $v) {
                $html .= "<tr>";
                foreach($title as $k1=>$v1){
                    $html .= "<td>".$v[$v1]."</td>";
                }
                $html .= "</tr>";
            }
        }
        
        $this->toxls($title, $html);
    }

    //导出所有sku（包含子sku）
    function exportAllSku() {
        @set_time_limit(0);
        $this->load->model('product_model');
        $rs = $this->product_model->selectSku($this->country);
        $html = array();
        if ($rs) {
            foreach ($rs as $k => $v) {
                $html[] = $k + 1 . ',' . $v;
            }
        }
        $html = join(PHP_EOL, $html);
        $this->tocsv(array('Index', 'SKU(选项)'), $html);
    }

    //删除重复tag2,tag3
    function delsametag($i = 0) {
        $this->load->model('product_model');
        $rs = $this->product_model->limit($i . ',1')->order('_id,asc')->select($this->country);
        if ($rs) {
            foreach ($rs as $vo) {
                $vo1['_id'] = (string) $vo['_id'];
                $vo1['tag']['Tag1'] = $vo['tag']['Tag1'];
                $tag2 = $vo1['tag']['Tag2'] = array_unique($vo['tag']['Tag2']);
                $tag3 = $vo1['tag']['Tag3'] = array_unique($vo['tag']['Tag3']);
                if (count($tag2) == count($vo['tag']['Tag2']) && count($tag3) == count($vo['tag']['Tag3'])) {
                    //没修改跳过
                    echo "产品:" . (string) $vo['_id'] . "无需处理，跳过。";
                } elseif (count($tag2) == count($vo['tag']['Tag2'])) {
                    $return = $this->product_model->update($this->country, $vo1);
                } elseif (count($tag3) == count($vo['tag']['Tag3'])) {
                    $return = $this->product_model->update($this->country, $vo1);
                } else {
                    $return = $this->product_model->update($this->country, $vo1);
                }
                if (isset($return)) {
                    if ($return) {
                        echo "产品:" . (string) $vo['_id'] . '去除重复tag成功';
                    } else {
                        echo "产品:" . (string) $vo['_id'] . '去除重复tag失败';
                    }
                }
            }
            $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
            $this->load->view('excel', $array);
        } else {
            echo "全部操作完成";
        }
    }

    //选项异常的产品
    function varianterror($i = 0) {
        $this->load->model('product_model');
        $rs = $this->product_model->limit($i . ',1')->order('_id,asc')->select($this->country);
        if ($rs) {
            $log = FCPATH . $this->country . 'varianterror.txt';
            foreach ($rs as $vo) {
                $p = $this->product_model->findOneSku($this->country, (string) $vo['_id']);
                if ($p) {
                    $p .= PHP_EOL;
                    file_put_contents($log, $p, FILE_APPEND);
                }
            }
            $array['goto'] = '/excel/' . __FUNCTION__ . '/' . ($i + 1);
            $this->load->view('excel', $array);
        } else {
            echo "over";
        }
    }

    //选项异常的产品
    function quickvarianterror() {
        @set_time_limit(0);
        $this->load->model('product_model');
        $count = $this->product_model->count($this->country);
        $rs = $this->product_model->limit('0,' . $count)->order('_id,asc')->select($this->country);
        if ($rs) {
            $log = FCPATH . $this->country . 'quickvarianterror.txt';
            foreach ($rs as $vo) {
                $p = $this->product_model->findOneSku($this->country, (string) $vo['_id']);
                if ($p) {
                    $p .= PHP_EOL;
                    file_put_contents($log, $p, FILE_APPEND);
                }
            }
            echo "over";
        } else {
            echo "没有产品";
        }
    }
    
    function variantsdouhao(){
        @set_time_limit(0);
        $this->load->model('product_model');
        $count = $this->product_model->count($this->country);
        $rs = $this->product_model->limit('0,' . $count)->order('_id,asc')->select($this->country);
        if ($rs) {
            $log = FCPATH . $this->country . 'quickvarianterror.txt';
            foreach ($rs as $vo) {
                $p = $this->product_model->findOneSku($this->country, (string) $vo['_id'],'',true);
                if ($p) {
                    $p .= PHP_EOL;
                    file_put_contents($log, $p, FILE_APPEND);
                }
            }
            echo "over";
        } else {
            echo "没有产品";
        }
    }

    //修复错误的子sku（属性）
    function recorychildSku() {
        $log = FCPATH . $this->country . 'quickvarianterror.txt';
        $log1 = FCPATH . $this->country . 'recoryvarianterror.txt';
        $content = @file_get_contents($log);
        @set_time_limit(0);
        $this->load->model('product_model');
        if (!empty($content)) {
            $content = explode(PHP_EOL, $content);
            $content = array_filter($content);
            if (!empty($content)) {
                foreach ($content as $k => $v) {
                    $_con = explode(',', $v);
                    if (strlen($_con[0]) != 24) {
                        file_put_contents($log1, $_con[1] . '的_id格式不正确' . PHP_EOL, FILE_APPEND);
                    } else {
                        $p = $this->product_model->findOneSku($this->country, $_con[0], true); //实际正确的
                        $rs = $this->product_model->finddetailsku($this->country, $_con[0]);
                        foreach ($rs as $kk => $vv) {
                            if (!empty($vv['details'])) {
                                foreach ($vv['details'] as $kk1 => $vv1) {
                                    if (isset($p[$kk1]) && !empty($p[$kk1])) {
                                        $vv['details'][$kk1]['sku'] = $p[$kk1];
                                        if(!isset($vv['details'][$kk1]['status'])){
                                            $vv['details'][$kk1]['status'] = 2;
                                        }
                                        if(!isset($vv['details'][$kk1]['stock'])){
                                            $vv['details'][$kk1]['stock'] = 0;
                                        }
                                    } else {
                                        unset($vv['details'][$kk1]);
                                    }
                                    unset($p[$kk1]);
                                }
                                if (!empty($p)) {
                                    foreach ($p as $kp => $vp) {
                                        $vv['details'][$kp] = array('status' => 2, 'price' => 0, 'original' => 0, 'bundle' => 0, 'stock' => 0, 'sku' => $vp);
                                    }
                                }
                            } elseif (!empty($p)) {
                                foreach ($p as $kp => $vp) {
                                    $vv['details'][$kp] = array('status' => 2, 'price' => 0, 'original' => 0, 'bundle' => 0, 'stock' => 0, 'sku' => $vp);
                                }
                            }
                            $updateWhere = array('_id' => $vv['_id']);
                            $updateParm = array('$set' => array('details' => $vv['details']));
                            $rs1 = $this->product_model->updatechildsku($this->country, $updateWhere, $updateParm);
                            if (!$rs1) {
                                file_put_contents($log1, $_con[1] . '数据更新失败' . PHP_EOL, FILE_APPEND);
                            }
                        }
                    }
                }
                echo "更新完成";
            } else {
                echo "没有发现更新的产品文档数据";
            }
        } else {
            echo "没有发现更新的产品文档数据";
        }
    }

    //修复children
    function recoryChildren() {
        $this->load->model('product_model');
        $count = $this->product_model->count($this->country);
        $rs = $this->product_model->limit('0,' . $count)->select($this->country, array('children' => 1));
        if ($rs) {
            foreach ($rs as $k => $v) {
                $detailsku = $v['details'];
                if (empty($detailsku)) {
                    $this->product_model->updateMainPro($this->country, array('_id' => $v['_id']), array('$set' => array('children' => 0)));
                }
            }
        }
        echo "over";
    }

    //删除hidden的选项属性
    function findhiddenattr() {
        $this->load->model('product_model');
        $rs = $this->product_model->selectHiddenSku($this->country);
        $this->tocsv(array(), join(PHP_EOL, $rs));
    }

    //查询没有产品的product type
    function noproductofproducttype() {
        @set_time_limit(0);
        $this->load->model('category_model');
        $this->load->model('product_model');
        $list = $this->category_model->listData();
        if ($list) {
            $log = FCPATH . $this->country . 'noproductofproducttype.txt';
            foreach ($list as $v) {
                $p = $this->product_model->getprobytype($this->country, $v['_id']);
                if (!$p) {
                    file_put_contents($log, $v['_id'] . ':' . $v['title'] . PHP_EOL, FILE_APPEND);
                }
            }
        }
        echo "over";
    }

    //查询隐藏的产品
    function hiddenPro() {
        $this->load->model('product_model');
        $a = $this->product_model->findhidden($this->country);
        if ($a) {
            $t = array();
            foreach ($a as $k => $v) {
                $t[] = $v['sku'];
            }
            $t = join(PHP_EOL, $t);
            $this->tocsv(array('sku'), $t);
        } else {
            echo "没有隐藏产品";
        }
    }

    //批量处理属性尺码中的变量，US->国家代码，数字->数字+2,新加坡(sg)，马来西亚(my)不需要加2
    function replaceattrvar() {
        $this->load->model('product_model');
        $count = $this->product_model->count($this->country);
        $rs = $this->product_model->limit('0,' . $count)->select($this->country);
        if ($rs) {
            if($this->country == 'GB')
                                $this->country = 'UK';
            $replace = $t = array();
            foreach ($rs as $k => $v) {
                $variants = $v['variants'];
                if ($variants) {
                    foreach ($variants as $k1 => $v1) {
                        $valuem_map = isset($v1['value_map']) && !empty($v1['value_map']) ? explode(',', $v1['value_map']) : array();
                        $option_map = isset($v1['option_map']) && !empty($v1['option_map']) ? $v1['option_map'] : '';
                        if (strtolower($option_map) == 'size' && !empty($valuem_map)) {
                            foreach ($valuem_map as $k2 => $v2) {
                                if($this->country=='SG'){
                                    $t[] = preg_replace( '/\((.*) (in|IN) (AU)\)/','',$v2);
                                }else{
                                    if (preg_match_all('/\((?:\d*\D+|(\d*)\/?(\d*)) (?:IN|in) (US)\)/', $v2, $matches)) {
                                        if ($this->country == 'MY'||$this->country=='NZ'||$this->country=='UK'){
                                            $t[] = str_replace(array($matches[3][0]), array($this->country), $v2);
                                        } else {
                                            if ($matches[2][0]!='' && $matches[1][0]!='') {
                                                if($matches[2][0]>$matches[1][0]){
                                                    $t[] = str_replace(array($matches[2][0], $matches[1][0], $matches[3][0]), array($matches[2][0] + 2, $matches[1][0] + 2, $this->country), $v2);
                                                }  else {
                                                    $t[] = str_replace(array($matches[1][0], $matches[2][0], $matches[3][0]), array($matches[1][0] + 2, $matches[2][0] + 2, $this->country), $v2);
                                                }
                                            } elseif ($matches[1][0]!='') {
                                                $t[] = str_replace(array($matches[1][0], $matches[3][0]), array($matches[1][0] + 2, $this->country), $v2);
                                            } else {
                                                $t[] = str_replace(array($matches[3][0]), array($this->country), $v2);
                                            }
                                        }
                                    } else {
                                        $t[] = $v2;
                                    }
                                }
                            }
                            if (!empty($t)) {
                                $replace[$k1] = join(',', $t);
                                $t = array();
                                if($this->country == 'UK')
                                    $country = 'GB';
                                else
                                    $country = $this->country;
                                if (!empty($replace)) {
                                    foreach ($replace as $kk => $vv) {
                                        $this->product_model->updatechildsku($country, array('_id' => $v['_id']), array('$set' => array('variants.' . $kk . '.value_map' => $vv)));
                                    }
                                    unset($replace);
                                }
                            }
                        }
                    }
                }
            }
            echo "批量处理完成";
        } else {
            echo "没有产品";
        }
    }
    
    function replaceattrvarAU() {
        $this->load->model('product_model');
        $count = $this->product_model->count($this->country);
        $rs = $this->product_model->limit('0,' . $count)->select($this->country);
        if ($rs) {
            if($this->country == 'GB')
                                $this->country = 'UK';
            $replace = $t = array();
            foreach ($rs as $k => $v) {
                $variants = $v['variants'];
                if ($variants) {
                    foreach ($variants as $k1 => $v1) {
                        $valuem_map = isset($v1['value_map']) && !empty($v1['value_map']) ? explode(',', $v1['value_map']) : array();
                        $option_map = isset($v1['option_map']) && !empty($v1['option_map']) ? $v1['option_map'] : '';
                        if (strtolower($option_map) == 'size' && !empty($valuem_map)) {
                            foreach ($valuem_map as $k2 => $v2) {
                                if($this->country=='SG'){
                                    $t[] = preg_replace( '/\((.*) (in|IN) (AU)\)/','',$v2);
                                }else{
                                    if (preg_match_all('/\((?:\d*\D+|(\d*)\/?(\d*)) (?:IN|in) (AU)\)/', $v2, $matches)) {
                                        if ($this->country == 'MY'||$this->country=='NZ'||$this->country=='UK') {
                                            $t[] = str_replace(array($matches[3][0]), array($this->country), $v2);
                                        } else {
                                            if ($matches[2][0]!='' && $matches[1][0]!='') {
                                                if($matches[2][0]<$matches[1][0]){
                                                    $t[] = str_replace(array($matches[2][0], $matches[1][0], $matches[3][0]), array($matches[2][0] - 2, $matches[1][0] - 2, $this->country), $v2);
                                                }  else {
                                                    $t[] = str_replace(array($matches[1][0], $matches[2][0], $matches[3][0]), array($matches[1][0] - 2, $matches[2][0] - 2, $this->country), $v2);
                                                }
                                            } elseif ($matches[1][0]!='') {
                                                $t[] = str_replace(array($matches[1][0], $matches[3][0]), array($matches[1][0] - 2, $this->country), $v2);
                                            } else {
                                                $t[] = str_replace(array($matches[3][0]), array($this->country), $v2);
                                            }
                                        }
                                    } else {
                                        $t[] = $v2;
                                    }
                                }  
                            }
                            if (!empty($t)) {
                                $replace[$k1] = join(',', $t);
                                $t = array();
                                if($this->country == 'UK')
                                    $country = 'GB';
                                else
                                    $country = $this->country;
                                if (!empty($replace)) {
                                    foreach ($replace as $kk => $vv) {
                                        $this->product_model->updatechildsku($country, array('_id' => $v['_id']), array('$set' => array('variants.' . $kk . '.value_map' => $vv)));
                                    }
                                    unset($replace);
                                }
                            }
                        }
                    }
                }
            }
            echo "批量处理完成";
        } else {
            echo "没有产品";
        }
    }
    
    function replaceattrvarproblem() {
        $this->load->model('product_model');
        $count = $this->product_model->count('US');
        $rs = $this->product_model->limit('0,' . $count)->select('US');
        if ($rs) {
            $replace = $t = array();
            foreach ($rs as $k => $v) {
                $variants = $v['variants'];
                if ($variants) {
                    $pro = $this->product_model->findPro($this->country,array('sku'=>$v['sku']));
                    foreach ($variants as $k1 => $v1) {
                        $valuem_map = isset($v1['value_map']) && !empty($v1['value_map']) ? explode(',', $v1['value_map']) : array();
                        $option_map = isset($v1['option_map']) && !empty($v1['option_map']) ? $v1['option_map'] : '';
                        if (strtolower($option_map) == 'size' && !empty($valuem_map)) {
                            foreach ($valuem_map as $k2 => $v2) {
                                if (preg_match_all('/\((?:\d*\D+|(\d*)\/?(\d*)) (?:IN|in) (US)\)/', $v2, $matches)) {
                                    if ($this->country == 'SG' || $this->country == 'MY') {
                                        $t[] = str_replace(array($matches[3][0]), array($this->country), $v2);
                                    } else {
                                        if ($matches[2][0]!='' && $matches[1][0]!='') {
                                            if($matches[2][0]>$matches[1][0]){
                                                $t[] = str_replace(array($matches[2][0], $matches[1][0], $matches[3][0]), array($matches[2][0] + 2, $matches[1][0] + 2, $this->country), $v2);
                                            }  else {
                                                $t[] = str_replace(array($matches[1][0], $matches[2][0], $matches[3][0]), array($matches[1][0] + 2, $matches[2][0] + 2, $this->country), $v2);
                                            }
                                        } elseif ($matches[1][0]!='') {
                                            $t[] = str_replace(array($matches[1][0], $matches[3][0]), array($matches[1][0] + 2, $this->country), $v2);
                                        } else {
                                            $t[] = str_replace(array($matches[3][0]), array($this->country), $v2);
                                        }
                                    }
                                } else {
                                    $t[] = $v2;
                                }
                            }
                            if (!empty($t)) {
                                $replace[$k1] = join(',', $t);
                                $t = array();
                                if (!empty($replace)) {
                                    foreach ($replace as $kk => $vv) {
                                        $this->product_model->updatechildsku($this->country, array('_id' => $pro['_id']), array('$set' => array('variants.' . $kk . '.value_map' => $vv)));
                                    }
                                    unset($replace);
                                }
                            }
                        }
                    }
                }
            }
            echo "批量处理完成";
        } else {
            echo "没有产品";
        }
    }

    function shoppingfeedempty() {
        $this->load->model('product_model');
        $count = $this->product_model->count($this->country);
        $rs = $this->product_model->limit('0,' . $count)->select($this->country,array('shopping_feed'=>''),'pro_append');
        if($rs){
            $tmp = '';
            foreach ($rs as $key => $value) {
                $u = site_url("/product/edit/".(string)$value['_id']);
               $tmp .= "<tr><td><a href='".$u."'>".$value['sku']."</a></td></tr>";
            }
            $this->toxls(array(), $tmp);
        }else{
            echo "没有产品";
        }
    }

    //批量导入topReview数据
    function importopreview($a = false) {
        $file = fopen('lastname2.csv', 'r');
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            $data = eval('return ' . iconv('gbk', 'utf-8', var_export($data, true)) . ';');
            $lastname[] = $data;
        }
        $file = fopen('female.csv', 'r');
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            $data = eval('return ' . iconv('gbk', 'utf-8', var_export($data, true)) . ';');
            $female[] = $data;
        }
        $file = fopen('male.csv', 'r');
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            $data = eval('return ' . iconv('gbk', 'utf-8', var_export($data, true)) . ';');
            $male[] = $data;
        }
        $file = fopen('reviewD.csv', 'r');
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            $data = eval('return ' . iconv('gbk', 'utf-8', var_export($data, true)) . ';');
            $review[] = $data;
        }
        unset($review[0]);
        fclose($file);
        //操作入库
        $this->load->model('product_model');
        if(!empty($review)){
            $i = 0;
            foreach ($review as $k=>$v){
                $product = $this->product_model->limit('0,1')->select($this->country,array('sku'=>  strtoupper($v[0])));
                if(empty($product)||empty($product[0])){
                    file_put_contents($this->country.'_noskuprduct.txt',$v[0].PHP_EOL,FILE_APPEND);
                    continue;
                }
                $product = $product[0];
                if (!$a && strip_tags(htmlspecialchars_decode($product['topreview']), '<img>') != '') {
                    continue;
                }
                //随机名字
                $malename = ucfirst(strtolower($male[mt_rand(0, count($male) - 1)][0])) . ' ' . ucfirst(strtolower($lastname[mt_rand(0, count($lastname) - 1)][0]));
                $femalename = ucfirst(strtolower($female[mt_rand(0, count($female) - 1)][0])) . ' ' . ucfirst(strtolower($lastname[mt_rand(0, count($lastname) - 1)][0]));
                $_name = array($malename, $femalename);
                if(strtolower($v[3])=='female'){
                    $comment_name = $femalename;//评论人
                }elseif(strtolower($v[3])=='male'){
                    $comment_name = $malename;//评论人
                }else{
                    $comment_name = $_name[mt_rand(0, 1)]; //评论人
                }
                
                //随机日期2014年5月1日-至今
                $start = strtotime('2014/05/01');
                $end = time();
                $comment_date = date('M d, Y', mt_rand($start, $end)); //评论日期
                $topreview = '';
                if($v[1]){
                    $topreview = '<b>'.$v[1].'</b><br><br>';
                }
                $topreview .= $v[2].'<br><br><p style="text-align:right;">——'.$comment_name.' made this review on '.$comment_date.'</p>';
                $i++;
                $condition = array('_id' => $product['_id']);
                $data = array('$set' => array('topreview' => htmlspecialchars($topreview)));
                $this->product_model->updateAppendPro($this->country, $condition, $data);
            }
            if($i==0){
                echo "没有产品可操作";
            }else{
                echo "处理完毕";
            }
        }else {
            echo "没有产品";
        }
    }
    
    function importskucategory() {
        $this->load->model('product_model');
        $cate = array(
                //映射关系表
        );
        if (!empty($cate)) {
            foreach ($cate as $k => $v) {
                $product = $this->product_model->limit('0,1')->select($this->country, array('sku' => strtoupper($k)));
                if (empty($product) || empty($product[0])) {
                    continue;
                }
                $product = $product[0];
                $condition = array('_id' => $product['_id']);
                $data = array('$set' => array('shopping_feed' => $v));
                $this->product_model->updateAppendPro($this->country, $condition, $data);
            }
            echo "over";
        } else {
            echo "没有数据";
        }
    }

    function tocsv($title, $html) {
        if (!empty($title)) {
            $title = join(',', $title) . PHP_EOL;
        } else {
            $title = '';
        }
        $html = $title . $html;
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $this->country . date('YmdHis') . '.csv');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $html;
    }
    
    //处理meta description
    function stripmetadescription($_id=''){
        $this->load->model('product_model');
        //$where = array('create_time'=>array('$gt'=>1450692000));
        $where = array();
        $count = $this->product_model->count($this->country,$where);
        if (strlen($_id) == 24) {
            $rs = $this->product_model->order('_id,asc')->select($this->country, array('_id' => new MongoId($_id)));
        } else {
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                $seo_desc = strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"),' ',$v['seo']['description'])));
                if(empty($seo_desc)){
                    $seo_desc = strip_tags(htmlspecialchars_decode(str_replace(array("\r\n", "\r", "\n"),' ',$v['description'])));
                }
                $updatewhere = array('_id'=>$v['_id']);
                $updateparam = array('$set'=>array('seo.description'=>mb_substr($seo_desc,0,160)));
                $this->product_model->updateAppendPro($this->country,$updatewhere,$updateparam);
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    function recoryvariantsvalue($_id=''){
        $this->load->model('product_model');
        //$where = array('create_time'=>array('$gt'=>1450692000));
        $where = array();
        $count = $this->product_model->count($this->country,$where);
        if (strlen($_id) == 24) {
            $rs = $this->product_model->order('_id,asc')->select($this->country, array('_id' => new MongoId($_id)));
        } else {
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                if($v['variants']){
                    $updatewhere = array('_id'=>$v['_id']);
                    foreach($v['variants'] as $k1=>$v1){
                        if($v1['value']){
                            $value = array_filter(explode(',',$v1['value']));
                            $new_value = array_map(function($v0){
                                return preg_replace( '/\((.*)\)/', '',$v0);
                            },$value);
                            $diff = array_diff($value, $new_value);
                            if(!empty($diff)){
                                $updateparam = array('$set'=>array('variants.'.$k1.'.value'=>join(',',$new_value)));
                                $this->product_model->updatechildsku($this->country,$updatewhere,$updateparam);
                            }
                        }
                    }
                }
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    //variants value带有()的产品
    function variantsvalueerror(){
        $this->load->model('product_model');
        //$where = array('create_time'=>array('$gt'=>1450692000));
        $where = array();
        $count = $this->product_model->count($this->country,$where);
        $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        if($rs){
            $tmp = array();
            foreach($rs as $k=>$v){
                if($v['variants']){
                    foreach($v['variants'] as $k1=>$v1){
                        if($v1['value']){
                            $value = array_filter(explode(',',$v1['value']));
                            $h = false;
                            foreach($value as $k2=>$v2){
                                if(!in_array($v['sku'],$tmp)&&  preg_match('/\((.*)\)/', $v2)){
                                    $tmp[] = $v['sku'];
                                    $h = true;
                                    break;
                                }
                            }
                            if($h){
                                break;
                            }
                        }
                    }
                }
            }
            $this->tocsv(array(),  join(PHP_EOL, $tmp));
        }else{
            echo "没有产品";
        }
    }
    
    //查询价格低于$price的产品
    function pricelimit($price='cost'){
        $this->load->model('product_model');
        //$where = array('create_time'=>array('$gt'=>1450692000));
        $where = array();
        $count = $this->product_model->count($this->country,$where);
        $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        if($rs){
            $tmp = array();
            foreach($rs as $k=>$v){
                if($price=='cost'){
                    if(!in_array($v['sku'],$tmp)&&$v['cost']<=0){
                        $tmp[]=$v['sku'];
                        break;
                    }
                }elseif($price=='price'){
                    if(!in_array($v['sku'],$tmp)&&!$v['price']){
                        $tmp[]=$v['sku'];
                        break;
                    }
                }elseif($price=='original'){
                    if(!in_array($v['sku'],$tmp)&&!$v['original']){
                        $tmp[]=$v['sku'];
                        break;
                    }
                }
                if($v['details']){
                    foreach($v['details'] as $k1=>$v1){
                        if($price=='cost'){
                            if(!in_array($v['sku'],$tmp)&&((isset($v1['cost'])&&$v1['cost']<=0))){
                                $tmp[]=$v['sku'];
                                break;
                            }
                            
                        }elseif($price=='price'){
                            if(!in_array($v['sku'],$tmp)&&(!$v1['price'])){
                                $tmp[]=$v['sku'];
                                break;
                            }
                        }elseif($price=='original'){
                            if(!in_array($v['sku'],$tmp)&&(!$v1['original'])){
                                $tmp[]=$v['sku'];
                                break;
                            }
                        }
                    }
                }
            }
            $this->tocsv(array(),  join(PHP_EOL, $tmp));
        }else{
            echo "没有产品";
        }
    }
    
    function minprice($_id=''){
        $this->load->model('product_model');
        //$where = array('create_time'=>array('$gt'=>1450692000));
        $where = array();
        if (strlen($_id) == 24) {
            $rs = $this->product_model->order('_id,asc')->select($this->country, array('_id' => new MongoId($_id)));
        } else {
            $count = $this->product_model->count($this->country,$where);
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                $details = $v['details'];
                if(!empty($details)){
                    $tmp = $tmp1 = array();
                    foreach($details as $k1=>$v1){
                        $tmp[] = isset($v1['price'])?(int)$v1['price']:'';
                        $tmp1[] = isset($v1['original'])?(int)$v1['original']:'';
                    }
                    $tmp = array_filter($tmp);
                    $tmp1 = array_filter($tmp1);
                    if(!empty($tmp)){
                        $min = min($tmp);
                        if($min>0){
                            $this->product_model->updateMainPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('price'=>$min)));
                        }
                    }
                    if(!empty($tmp1)){
                        $min1 = min($tmp1);
                        if($min1>0){
                            $this->product_model->updateMainPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('original'=>$min1)));
                        }
                    }
                }
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    function shoppingcate(){
        $cate = array(
//            'Cushion Case '=>'Home & Garden > Decor > Chair & Sofa Cushions',
//            'Bathroom'=>'Home & Garden > Linens & Bedding > Towels > Bath Towels & Washcloths',
//            'Bedding'=>'Home & Garden > Linens & Bedding > Bedding > Bed Sheets',
//            'Bedroom'=>'Home & Garden > Linens & Bedding > Bedding > Bed Sheets',
//            'PRO-10343
//            PRO-10342
//            PRO-10344
//            PRO-10345
//            PRO-10346
//            PRO-764'=>'Hardware > Plumbing > Plumbing Fixture Hardware & Parts > Shower Parts > Shower Heads',
//            'PRO-10083
//            PRO-10250
//            PRO-10251
//            PRO-10256
//            PRO-10275
//            PRO-10296
//            PRO-10287
//            PRO-10288
//            PRO-10340
//            PRO-10339
//            PRO-10337
//            PRO-10338
//            PRO-10341'=>'Hardware > Plumbing > Plumbing Fixture Hardware & Parts > Faucet Accessories',
//            'Storage & Organizer'=>'Home & Garden > Household Supplies > Storage & Organization',
//            'Cleaning & Vacuum'=>'Home & Garden > Household Appliance Accessories > Floor & Steam Cleaner Accessories',
//            'Garden & Outdoor'=>'Home & Garden > Lawn & Garden',
//            'Lighting & Lamping'=>'Home & Garden > Lighting',
//            'Home Decor'=>'Home & Garden > Decor',
//            'Women\'s Fashion'=>'Apparel & Accessories > Clothing',
//            'Men\'s Fashion'=>'Apparel & Accessories > Clothing',
//            'Handbags & Wallets'=>'Apparel & Accessories > Handbags Wallets & Cases',
//            'Shoulder Bags'=>'Apparel & Accessories > Handbag & Wallet Accessories',
//            'Watches'=>'Apparel & Accessories > Jewelry > Watches',
//            'Hair Care'=>'Apparel & Accessories > Clothing Accessories > Hair Accessories',
//            'Jewelry'=>'Apparel & Accessories > Jewelry',
//            'Novelty'=>'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts > Art & Crafting Materials',
//            'Flash Drive&Media'=>'Electronics > Electronics Accessories > Computer Components > Storage Devices > USB Flash Drives',
//            'Car & Accessories'=>'Electronics > Electronics Accessories',
//            'Camera & Accessories'=>'Cameras & Optics > Camera & Optic Accessories',
//            'Mobile & Apple Accessories'=>'Electronics > Computers > Tablet Computers',
//            'PC & Tablet Accessories'=>'Electronics > Electronics Accessories > Computer Accessories',
//            'Speaker & Headset'=>'Electronics > Audio > Audio Accessories > Headphone & Headset Accessories',
//            'Smart Watch&Braclet'=>'Apparel & Accessories > Jewelry > Watches',
//            'Video & Projector'=>'Electronics > Video > Projectors',
//            'Yoga & Fitness'=>'Health & Beauty > Health Care > Fitness & Nutrition',
//            'Travel & Outdoors'=>'Electronics > Electronics Accessories > Power > Travel Converters & Adapters',
//            'Swimsuit&Bikini'=>'Apparel & Accessories > Clothing > Swimwear',
//            'Cycling'=>'Sporting Goods > Outdoor Recreation > Cycling',
//            'Men\'s Masturbator'=>'Health & Beauty > Personal Care > Cosmetics > Bath & Body > Adult Hygienic Wipes',
//            'Vibrators'=>'Health & Beauty > Personal Care > Cosmetics > Bath & Body > Adult Hygienic Wipes',
//            'Sexy Lingerie'=>'Health & Beauty > Personal Care > Cosmetics > Bath & Body > Adult Hygienic Wipes',
//            'Condoms'=>'Health & Beauty > Personal Care > Cosmetics > Bath & Body > Adult Hygienic Wipes',
//            'Adult'=>'Health & Beauty > Personal Care > Cosmetics > Bath & Body > Adult Hygienic Wipes',
//            'Baby'=>'Baby & Toddler > Baby Health',
//            'Education'=>'Toys & Games > Toys > Educational Toys',
//            'Toy & Accessories'=>'Toys & Games > Toys',
//            'Children Cloth'=>'Apparel & Accessories > Clothing > Baby & Toddler Clothing',
//            'Slimming'=>'Apparel & Accessories > Clothing > Underwear & Socks > Shapewear',
//            'Healthcare'=>'Health & Beauty > Health Care'
            'Handbags'=>'Apparel & Accessories > Handbags, Wallets & Cases',
            'Backpacks & Shoulder Bags'=>'Luggage & Bags > Backpacks'
        );
        
        $this->load->model('collection_model');
        $this->load->model('product_model');
        $tis = array();
        foreach($cate as $title=>$shopping_feed){
            if(strpos($title,'PRO')===0){
                $ti = explode(PHP_EOL,$title);
                foreach ($ti as $k=>$v){
                    $info = $this->product_model->limit('0,1')->select($this->country,array('sku'=>trim($v)));
                    $tis[] = $info[0]['_id'];
                    $this->product_model->updateAppendPro($this->country,array('_id'=>$info[0]['_id']),array('$set'=>array('shopping_feed'=>htmlspecialchars($shopping_feed))));
                }
            }
        }
        $error = array();
        foreach($cate as $title=>$shopping_feed){
            if(strpos($title,'PRO')!==0){
                $allowpro = $this->collection_model->getInfoByTitle($this->country,$title,array('allow'=>1,'_id'=>0));
                if(!empty($allowpro)&&!empty($allowpro['allow'])){
                    $indata = array_diff($allowpro['allow'],$tis);
                    $where = array('_id'=>array('$in'=>$indata));
                    $rs = $this->product_model->updateAppendPro($this->country,$where,array('$set'=>array('shopping_feed'=>  htmlspecialchars($shopping_feed))));
                    if(!$rs){
                        $error[] = array_diff($allowpro['allow'],$tis);
                    }
                }
            }
        }
        echo "over";
    }
    
    function repacetitle($_id='',$title='',$replace='',$tag2 = ''){
        if(empty($title)||empty($replace))exit('没有操作数据');
        $this->load->model('product_model');
        $where = array('title'=>new MongoRegex('/'.$title.'/'));
        if($tag2){
            $where['tag.Tag2'] = urldecode($tag2);
        }
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            $rs = array();
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where['_id'] = array('$in'=>$allowpro['allow']);
                $count = $this->product_model->count($this->country,$where);
                $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
            }
        }elseif (strlen($_id) == 24) {
            $where['_id'] = new MongoId($_id);
            $rs = $this->product_model->order('_id,asc')->select($this->country,$where);
        } else {
            $count = $this->product_model->count($this->country,$where);
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                $_title = str_replace($title,$replace,$v['title']);
                $_seotitle = str_replace($title,$replace,$v['seo']['title']);
                $this->product_model->updateMainPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('title'=>$_title)));
                $this->product_model->updateAppendPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('seo.title'=>$_seotitle)));
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    function repacedesc($_id='',$title='',$replace='',$tag2 = ''){
        if(empty($title)||empty($replace))exit('没有操作数据');
        $this->load->model('product_model');
        $where = array('description'=>new MongoRegex('/'.$title.'/'));
        $tmp = array();
        if($tag2){
            $count = $this->product_model->count($this->country,array('tag.Tag2'=>  urldecode($tag2)));
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,array('tag.Tag2'=>  urldecode($tag2)));
            if($rs){
                foreach($rs as $z=>$w){
                    $tmp[] = $w['_id'];
                }
            }
        }
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            $rs = array();
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                if(!empty($tmp)){
                    $allowpro['allow'] = array_values(array_intersect($allowpro['allow'], $tmp));
                }
                $where['_id'] = array('$in'=>$allowpro['allow']);
                $count = $this->product_model->count($this->country,$where,'pro_append');
                $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where.'pro_append');
            }
        }elseif (strlen($_id) == 24) {
            $where['_id'] = new MongoId($_id);
            if(!empty($tmp)&&!in_array($where['_id'], $tmp)){
                $rs = array();
            }else{
                $rs = $this->product_model->order('_id,asc')->select($this->country,$where,'pro_append');
            }
        } else {
            if(!empty($tmp)){
                $where['_id'] = array('$in'=>$tmp);
            }
            $count = $this->product_model->count($this->country,$where,'pro_append');
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where,'pro_append');
        }
        if($rs){
            foreach($rs as $k=>$v){
                $_description = str_replace($title,$replace,$v['description']);
                $_seodescription = str_replace($title,$replace,$v['seo']['description']);
                $this->product_model->updateAppendPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('seo.description'=>$_seodescription,'description'=>$_description)));
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    function initsold(){
        $this->load->model('product_model');
        $where = array('sold.number'=>array('$gt'=>0));
        $count = $this->product_model->count($this->country,$where);
        $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        if($rs){
            foreach($rs as $k=>$v){
                $this->product_model->updateMainPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('sold.number'=>new MongoInt64(0),'sold.total'=>new MongoInt64($v['sold']['init']))));
            }
            echo "over";
        }else{
            echo "没有产品";
        }
        
    }
    
    function handlecontent($htmlspecialchars = 1){
        $this->load->model('product_model');
        if($htmlspecialchars){
            $oreg = '/('.  htmlspecialchars('<h3').'.+?)color: rgb\(0, 0, 0\);(.*?'.  htmlspecialchars('>').'.+?'.  htmlspecialchars('<\/h3>').')/i';
        }else{
            $oreg = '/(<h3.+?)color: rgb\(0, 0, 0\);(.*?>.+?<\/h3>)/i';
        }
        $where = array('description'=>new MongoRegex($oreg));
        $count = $this->product_model->count($this->country,$where,'pro_append');
        $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where,'pro_append');
        if($rs){
            foreach($rs as $k=>$v){
                $desc = preg_replace($oreg,'$1$2', $v['description']);
                $this->product_model->updateAppendPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('description'=>$desc)));
            }
            echo "over";
        }else{
            echo "没有产品";
        }
        
    }
    
    function seo_title(){
        $this->load->model('product_model');
        $where = array('seo.title'=>'');
        $count = $this->product_model->count($this->country,$where,'pro_append');
        $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where,'pro_append');
        if($rs){
            foreach($rs as $k=>$v){
                if(empty($v['seo']['title'])){
                    $this->product_model->updateAppendPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('seo.title'=>$v['title'])));
                }
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    function variantsmaperror(){
        $this->load->model('product_model');
        $where = array();
        $count = $this->product_model->count($this->country,$where,'pro_details');
        $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where,'pro_details');
        if($rs){
            foreach($rs as $k1=>$v1){
                if(!empty($v1['variants'])){
                    foreach($v1['variants'] as $k=>$v){
                        if(isset($v['option'])&&isset($v['value'])&&isset($v['value_map'])&&strtolower($v['option'])!='size'&&$v['value']!=$v['value_map']){
                            //$a[] = array('_id'=>$v1['_id'],'variants'=>$v1['variants']);
                            foreach($v1['variants'] as $kk=>$vv){
                                if(strtolower($vv['option']!='size')&&$vv['value']!=$vv['value_map']){
                                    $v1['variants'][$kk]['value_map'] = $vv['value'];
                                }
                            }
                        }
                    }
                    $this->product_model->updatechildsku($this->country,array('_id'=>$v1['_id']),array('$set'=>array('variants'=>$v1['variants'])));
                }
            }

            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    function endprice99($_id=''){
        $this->load->model('product_model');
        $where = array();
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            $rs = array();
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where['_id'] = array('$in'=>$allowpro['allow']);
                $count = $this->product_model->count($this->country,$where);
                $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
            }
        }elseif (strlen($_id) == 24) {
            $where['_id'] = new MongoId($_id);
            $rs = $this->product_model->order('_id,asc')->select($this->country,$where);
        } else {
            $count = $this->product_model->count($this->country,$where);
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                $p1 = $v['price']/100;
                if(is_int($p1))$p1 -= 1;
                $price = new MongoInt64((intval($p1)+0.99)*100);
                $this->product_model->updateMainPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('price'=>$price)));
                if(!empty($v['details'])){
                    foreach($v['details'] as $k1=>$v1){
                        $p2 = $v1['price']/100;
                        if(is_int($p2))$p2 -= 1;
                        $v['details'][$k1]['price'] = new MongoInt64((intval($p2)+0.99)*100);
                    }
                    $this->product_model->updatechildsku($this->country,array('_id'=>$v['_id']),array('$set'=>array('details'=>$v['details'])));
                }
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
    function recorysold($_id=''){
        $this->load->model('product_model');
        $where = array('sold.number'=>array('$exists'=>false));
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            $rs = array();
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where['_id'] = array('$in'=>$allowpro['allow']);
                $count = $this->product_model->count($this->country,$where);
                $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
            }
        }elseif (strlen($_id) == 24) {
            $where['_id'] = new MongoId($_id);
            $rs = $this->product_model->order('_id,asc')->select($this->country,$where);
        } else {
            $count = $this->product_model->count($this->country,$where);
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                $v['sold']['number'] = new MongoInt64(0);
                $this->product_model->updateMainPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('sold'=>$v['sold'])));
            }
            echo "over";
        }else{
            echo "无产品";
        }
    }
    
    function appenddesc($_id='',$content='',$tag2='',$paichu=''){
        if(!$content)exit('over');
        $content = @hex2bin($content);
        if(!$content)exit('over');
        $this->load->model('product_model');
        if($tag2){
            $where = array('tag.Tag2'=>  urldecode($tag2));
        }else{
            $where = [];
        }
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            $rs = array();
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where['_id'] = array('$in'=>$allowpro['allow']);
                $count = $this->product_model->count($this->country,$where);
                $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
            }
        }elseif (strlen($_id) == 24) {
            $where['_id'] = new MongoId($_id);
            $rs = $this->product_model->order('_id,asc')->select($this->country,$where);
        } else {
            $count = $this->product_model->count($this->country,$where);
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                if($paichu&&$paichu==(string)$v['_id']){
                    continue;
                }
                $desc = $v['description'].$content;
                $this->product_model->updateAppendPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('description'=>$desc)));
            }
            echo "over";
        }else{
            echo "无产品";
        }
    }
    
    function beforedesc($_id='',$content='',$tag2='',$paichu=''){
        if(!$content)exit('over');
        $content = @hex2bin($content);
        if(!$content)exit('over');
        $this->load->model('product_model');
        if($tag2){
            $where = array('tag.Tag2'=>  urldecode($tag2));
        }else{
            $where = [];
        }
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            $rs = array();
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where['_id'] = array('$in'=>$allowpro['allow']);
                $count = $this->product_model->count($this->country,$where);
                $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
            }
        }elseif (strlen($_id) == 24) {
            $where['_id'] = new MongoId($_id);
            $rs = $this->product_model->order('_id,asc')->select($this->country,$where);
        } else {
            $count = $this->product_model->count($this->country,$where);
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                if($paichu&&$paichu==(string)$v['_id']){
                    continue;
                }
                $desc = $content.$v['description'];
                $this->product_model->updateAppendPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('description'=>$desc)));
            }
            echo "over";
        }else{
            echo "无产品";
        }
    }
    
    function deldesc($_id='',$content='',$tag2='',$paichu=''){
        if(!$content)exit('over');
        $content = @hex2bin($content);
        if(!$content)exit('over');
        $this->load->model('product_model');
        if($tag2){
            $where = array('tag.Tag2'=>  urldecode($tag2));
        }else{
            $where = [];
        }
        if(strlen($_id)==13){
            $this->load->model('collection_model');
            $allowpro = $this->collection_model->getInfoById($this->country,$_id,array('allow'=>1,'_id'=>0));
            $rs = array();
            if(!empty($allowpro)&&!empty($allowpro['allow'])){
                $where['_id'] = array('$in'=>$allowpro['allow']);
                $count = $this->product_model->count($this->country,$where);
                $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
            }
        }elseif (strlen($_id) == 24) {
            $where['_id'] = new MongoId($_id);
            $rs = $this->product_model->order('_id,asc')->select($this->country,$where);
        } else {
            $count = $this->product_model->count($this->country,$where);
            $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where);
        }
        if($rs){
            foreach($rs as $k=>$v){
                if($paichu&&$paichu==(string)$v['_id']){
                    continue;
                }
                $desc = str_replace($content,'', $v['description']);
                $this->product_model->updateAppendPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('description'=>$desc)));
            }
            echo "over";
        }else{
            echo "无产品";
        }
    }
    
    function recoryseotitle(){
        $this->load->model('product_model');
        $where = array('seo.title'=>new MongoRegex('/-/'));
        $count = $this->product_model->count($this->country,$where,'pro_append');
        $rs = $this->product_model->order('_id,asc')->limit('0,' . $count)->select($this->country,$where,'pro_append');
        if($rs){
            foreach($rs as $k=>$v){
                if(!empty($v['seo']['title'])){
                    $seotitle = str_replace('-', ' ', $v['seo']['title']);
                    $this->product_model->updateAppendPro($this->country,array('_id'=>$v['_id']),array('$set'=>array('seo.title'=>$seotitle)));
                }
            }
            echo "over";
        }else{
            echo "没有产品";
        }
    }
    
}

?>