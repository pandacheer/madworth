<?php
$config = array(
		'member'=>array(
               array(
                     'field'   => 'email', 
                     'label'   => 'email', 
                     'rules'   => 'required|valid_email'
                  ),
               array(
                     'field'   => 'password', 
                     'label'   => 'Password', 
                     'rules'   => 'required|min_length[5]|max_length[16]|alpha_numeric'
             
              		 ),
             
              
               	),
		'receive'=>array(
               array(
                     'field'   => 'name', 
                     'label'   => 'name', 
                     'rules'   => 'required|min_length[1]|max_length[15]'
                  ),
               array(
                     'field'   => 'receive_company', 
                     'label'   => 'company', 
                     'rules'   => 'required|min_length[1]|max_length[30]'
             
              		 ),
                 array(
                     'field'   => 'receive_country', 
                     'label'   => 'country', 
                     'rules'   => 'required|min_length[1]|max_length[30]'
             
              		 ),
                   array(
                     'field'   => 'receive_city', 
                     'label'   => 'city', 
                     'rules'   => 'required|min_length[1]|max_length[10]'
             
              		 ),
                   array(
                     'field'   => 'receive_add1', 
                     'label'   => 'receive_add1', 
                     'rules'   => 'required|min_length[1]|max_length[10]'
             
              		 ),
                    array(
                     'field'   => 'receive_add2', 
                     'label'   => 'receive_add2', 
                     'rules'   => 'required|min_length[1]|max_length[10]'
             
              		 ),
                     array(
                     'field'   => 'receive_zipcode', 
                     'label'   => 'zipcode', 
                     'rules'   => 'required|min_length[1]|max_length[10]'
             
              		 ),
              		  array(
                     'field'   => 'receive_phone', 
                     'label'   => 'phone', 
                     'rules'   => 'required|min_length[1]|max_length[20]'
             
              		 )
                ),

'resetPssword'=>array(
                 array(
                     'field'   => 'password', 
                     'label'   => 'password', 
                     'rules'   => 'required|min_length[5]|max_length[16]|alpha_numeric'
                    )
                   )
               
            );