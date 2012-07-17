<?php
/**
* 
*/
class Kurs
{
    public function bca()
    {
        $product = null;
        $kurs = array();

        $html = @file_get_contents('http://www.bca.co.id/id/biaya-limit/kurs_counter_bca/kurs_counter_bca_landing.jsp');
        if($html == false){
           return false;
        }
        phpQuery::newDocumentHTML($html, $charset = 'utf-8');
        $mataUang = '';
        for ($i=7; $i <= 48 ; $i++) {
            
            if(($i-1)%3 == 0){
               $mataUang = pq("td:eq($i)")->html();
            }
            else if($i%3 != 0){
                $kurs[$mataUang]['jual'] = pq("td:eq($i)")->html();
            }
            else{
                $kurs[$mataUang]['beli'] = pq("td:eq($i)")->html();
            }
        }

        $data = array(
            'updated_at' => time(),
            'kurs'       => $kurs
            );
        return $data;
    }
}