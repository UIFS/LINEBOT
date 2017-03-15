<?php

$access_token = '0HSB7lPEM4zUCOYk9qQVNRJJndXzEeVW59UdLPzKN+NVPUAvbA9RCHCR3+pR57bHMZM1gsneTm5Dzd6iLH9fCJJlKRjD6T5Y0ierWK0e9vdgJ6tvqUMAA9tC6PsbsTYtzcpcYEHjTo+RWLMg0HrLHgdB04t89/1O/w1cDnyilFU=';
$proxy = 'velodrome.usefixie.com:80';
$proxyauth = 'fixie:gR62E1MpgXhcKpo';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if($text_ex[0] == "อยากรู้"){ //ถ้าข้อความคือ "อยากรู้" ให้ทำการดึงข้อมูลจาก Wikipedia หาจากไทยก่อน
            //https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=PHP
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_URL, 'https://th.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text_ex[1]);
            $result1 = curl_exec($ch1);
            curl_close($ch1);
            
            $obj = json_decode($result1, true);
            
            foreach($obj['query']['pages'] as $key => $val){

                $result_text = $val['extract'];
            }
            
            if(empty($result_text)){//ถ้าไม่พบให้หาจาก en
                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch1, CURLOPT_URL, 'https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text_ex[1]);
                $result1 = curl_exec($ch1);
                curl_close($ch1);
                
                $obj = json_decode($result1, true);
                
                foreach($obj['query']['pages'] as $key => $val){
                
                    $result_text = $val['extract'];
                }
            }
            if(empty($result_text)){//หาจาก en ไม่พบก็บอกว่า ไม่พบข้อมูล ตอบกลับไป
                $result_text = 'ไม่พบข้อมูล';
            }
            $response_format_text = ['contentType'=>1,"toType"=>1,"text"=>$result_text];
            
        }else if($text_ex[0] == "อากาศ"){//ถ้าพิมพ์มาว่า อากาศ ก็ให้ไปดึง API จาก wunderground มา
            //http://api.wunderground.com/api/yourkey/forecast/lang:TH/q/Thailand/%E0%B8%81%E0%B8%A3%E0%B8%B8%E0%B8%87%E0%B9%80%E0%B8%97%E0%B8%9E%E0%B8%A1%E0%B8%AB%E0%B8%B2%E0%B8%99%E0%B8%84%E0%B8%A3.json
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch1, CURLOPT_URL, 'http://api.wunderground.com/api/yourkey/forecast/lang:TH/q/Thailand/'.str_replace(' ', '%20', $text_ex[1]).'.json');
            $result1 = curl_exec($ch1);
            curl_close($ch1);
            
            $obj = json_decode($result1, true);
            if(isset($obj['forecast']['txt_forecast']['forecastday'][0]['fcttext_metric'])){
                $result_text = $obj['forecast']['txt_forecast']['forecastday'][0]['fcttext_metric'];
            }else{//ถ้าไม่เจอกับตอบกลับว่าไม่พบข้อมูล
                $result_text = 'ไม่พบข้อมูล';
            }
            
            $response_format_text = ['contentType'=>1,"toType"=>1,"text"=>$result_text];
        }else if($text == 'บอกมา'){//คำอื่นๆ ที่ต้องการให้ Bot ตอบกลับเมื่อโพสคำนี้มา เช่นโพสว่า บอกมา ให้ตอบว่า ความลับนะ
            $response_format_text = ['contentType'=>1,"toType"=>1,"text"=>"ความลับนะ"];
        }else{//นอกนั้นให้โพส สวัสดี
            $response_format_text = ['contentType'=>1,"toType"=>1,"text"=>"สวัสดี"];
        }

			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";
