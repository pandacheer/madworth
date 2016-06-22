<?php

/**
 * @文件： shipformula_model
 * @时间： 2015-7-15 9:46:55
 * @编码： utf8
 * @作者： fire.bruin
 * @QQ：   305046350
 * @emai： 305046350@qq.com
 * 说明：运费公式
 */
class Shipformula_model extends CI_Model {

    function insert($insertArr) {
        if ($this->db->insert('ship_formula', $insertArr)) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    function update($whereArr, $updateArr) {
        $this->db->where($whereArr);
        return $this->db->update('ship_formula', $updateArr);
    }

    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('ship_formula');
    }

    // 计算运费(返回人民币)
    function calculateShipping($country_code, $weight) {
        $sql = 'select formula,special from ship_formula where country_code= ? and weight>= ? order by weight limit 1';
        $query = $this->db->query($sql, array($country_code, $weight));
        $shipFormulaInfo = $query->row_array();
        if ($shipFormulaInfo) {
            $shipFormulaInfo['formula'] = str_replace('x', $weight, $shipFormulaInfo['formula']);
            eval("\$shipping = {$shipFormulaInfo['formula']};");
//            if ($shipFormulaInfo['special'] == 1) {
//                $shipFormulaInfo['formula'] = str_replace('x', $weight, $shipFormulaInfo['formula']);
//                eval("\$shipping = {$shipFormulaInfo['formula']};");
//            } else {
//                if ($weight < 1000.01) {
//                    $replaceStr = 0;
//                } else {
//                    $replaceStr = ceil(($weight - 1000) / 1000);
//                }
//                $shipFormulaInfo['formula'] = str_replace('(x-1000)/1000', $replaceStr, $shipFormulaInfo['formula']);
//                eval("\$shipping = {$shipFormulaInfo['formula']};");
//            }
            return $shipping;
        } else {
            return false;
        }
//        $total_price = $product_price + $shipping;
//
//        $this->load->model('country_model');
//        $priceFormula = $this->country_model->getCountryList('price_formula');
//        $priceFormula[$country_code] = str_replace('x', $total_price, $priceFormula[$country_code]);
//        eval("\$price = {$priceFormula[$country_code]};");
//        return $price;
    }

}
