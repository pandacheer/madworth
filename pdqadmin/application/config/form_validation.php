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
             
              		 )
               	)
            );