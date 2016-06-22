<?php
 class Vcode {
  private $width; //��
  private $height; //��
  private $num;  //����
  private $code; //��֤��
  private $img;  //ͼ�����Դ
  
  //���췽���� ��������
  function __construct($width=130, $height=40, $num=4) {
   $this->width = $width;
   $this->height = $height;
   $this->num = $num;
   $this->code = $this->createcode(); //�����Լ��ķ���
  }
  
  //��ȡ�ַ�����֤�룬 ���ڱ����ڷ�������
  function getcode() {
   return $this->code;
  }
  
  //���ͼ��
  function outimg() {
   //�������� (��ɫ�� ��С�� �߿�)
   $this->createback();
  
   //���� (��С�� ������ɫ)
   $this->outstring();
  
   //����Ԫ��(�㣬 ����)
  
   $this->setdisturbcolor();
   //���ͼ��
   $this->printimg();
  }
  
  //��������
  private function createback() {
   //������Դ
   $this->img = imagecreatetruecolor($this->width, $this->height);
   //��������ı�����ɫ
   $bgcolor = imagecolorallocate($this->img, rand(225, 255), rand(225, 255), rand(225, 255)); 
   //���ñ������
   imagefill($this->img, 0, 0, $bgcolor);
   //���߿�
   $bordercolor = imagecolorallocate($this->img, 0, 0, 0);
  
    imagerectangle($this->img, 0, 0, $this->width-1, $this->height-1, $bordercolor);
  }
  
  //����
  private function outstring() {
   for($i=0; $i<$this->num; $i++) {
  
    $color= imagecolorallocate($this->img, rand(0, 128), rand(0, 128), rand(0, 128)); 
  
    $fontsize=rand(10,13); //�����С
  
    $x = 3+($this->width/$this->num)*$i; //ˮƽλ��
    $y = rand(0, imagefontheight($fontsize)-3);
  
    //����ÿ���ַ�
    imagechar($this->img, $fontsize, $x, $y, $this->code{$i}, $color);
   }
  }
  
  //���ø���Ԫ��
  private function setdisturbcolor() {
   //���ϵ���
   for($i=0; $i<100; $i++) {
    $color= imagecolorallocate($this->img, rand(0, 255), rand(0, 255), rand(0, 255)); 
    imagesetpixel($this->img, rand(1, $this->width-2), rand(1, $this->height-2), $color);
   }
  
   //������
   for($i=0; $i<10; $i++) {
    $color= imagecolorallocate($this->img, rand(0, 255), rand(0, 128), rand(0, 255)); 
    imagearc($this->img,rand(-10, $this->width+10), rand(-10, $this->height+10), rand(30, 300), rand(30, 300), 55,44, $color);
   }
  }
  
  //���ͼ��
  private function printimg() {
   if (imagetypes() & IMG_GIF) {
     header("Content-type: image/gif");
     imagegif($this->img);
   } elseif (function_exists("imagejpeg")) {
     header("Content-type: image/jpeg");
     imagegif($this->img);
   } elseif (imagetypes() & IMG_PNG) {
     header("Content-type: image/png");
     imagegif($this->img);
   } else {
     die("No image support in this PHP server");
   } 
  
  }
  
  //������֤���ַ���
  private function createcode() {
   $codes = "3456789abcdefghijkmnpqrstuvwxyABCDEFGHIJKLMNPQRSTUVWXY";
  
   $code = "";
  
   for($i=0; $i < $this->num; $i++) {
    $code .=$codes{rand(0, strlen($codes)-1)}; 
   }
  
   return $code;
  }
  
  //�����Զ�����ͼ����Դ
  function __destruct() {
   imagedestroy($this->img);
  }
  
 }