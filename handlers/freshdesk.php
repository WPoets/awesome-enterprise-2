<?php
namespace aw2\freshdesk;

\aw2_library::add_service('freshdesk_api','Freshdesk api support',['namespace'=>__NAMESPACE__]);


\aw2_library::add_service('freshdesk_api.call','returns the login URL for linkedin',['func'=>'_call','namespace'=>__NAMESPACE__]);

function _call($atts,$content=null,$shortcode){
	if(\aw2_library::pre_actions('all',$atts,$content)==false)return;
	extract(shortcode_atts( array(
	'api_key'=>null,
    'method' => "GET",
	'domain'=>null,
	'data'=>null,
	'url_segment'=>null
	), $atts) );
	
	
    if(empty($api_key)) {
        $msg='api_key is missing';
		return $msg;
    };
	if(empty($domain) || empty($url_segment)) {
       $msg='domain or url_segment is missing';
		return $msg;
    };	

	$api_url = 'https://'.$domain.'.freshdesk.com/api/v2/'.$url_segment;
		
	$return_value='';
        
    $ch = curl_init($api_url);

    $header[] = "Content-type: application/json";
    if($method!='GET'){
		if($method=='POST'){
			curl_setopt($ch, CURLOPT_POST, true);
		}
		else{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		}
	}
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $api_key.":X");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);
    
    // Check the return value of curl_exec(), too
    if ($server_output === false) {
        throw new \Exception(curl_error($ch), curl_errno($ch));
    }
    
    $info = curl_getinfo($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($server_output, 0, $header_size);
    $return_value = substr($server_output, $header_size);
    
   	
    if($info['http_code'] == 201) {
    //success
    } else {
        if($info['http_code'] == 404) {
            $return_value='Error, Please check the end point => '.$api_url;

        } else {
	
            $return_value="Error, HTTP Status Code : " . $info['http_code'] . " Response is " . $return_value;
        }

    }
   	curl_close($ch);

    $return_value=\aw2_library::post_actions('all',$return_value,$atts);
    return $return_value;	
}
