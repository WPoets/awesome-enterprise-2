<?php
namespace aw2\global_cache;

\aw2_library::add_service('global_cache','Global Cache Library',['namespace'=>__NAMESPACE__]);

\aw2_library::add_service('global_cache.set','Set the Global Cache',['namespace'=>__NAMESPACE__]);

function set($atts,$content=null,$shortcode){
	if(\aw2_library::pre_actions('all',$atts,$content)==false)return;
	
	extract( shortcode_atts( array(
	'key'=>null,
	'prefix'=>'',
	'ttl' => 300
	), $atts) );
	
	if(!isset($atts['value']))$value=$content;
	else
	$value=$atts['value'];	
	$redis = \aw2_library::redis_connect(REDIS_DATABASE_GLOBAL_CACHE);
	
	if(!$key)return 'Invalid Key';		
	if($prefix)$key=$prefix . $key;
	
	$redis->set($key, $value);
	$redis->expire($key, $ttl*60);
	return;
}


\aw2_library::add_service('global_cache.get','Get the Global Cache',['namespace'=>__NAMESPACE__]);
function get($atts,$content=null,$shortcode=null){
	if(\aw2_library::pre_actions('all',$atts,$content)==false)return;
	
	extract( shortcode_atts( array(
	'main'=>null,
	'prefix'=>'',
	), $atts) );
	
	if(!$main)return 'Main must be set';		
	if($prefix)$main=$prefix . $main;
	//Connect to Redis and store the data
	$redis = \aw2_library::redis_connect(REDIS_DATABASE_GLOBAL_CACHE);
		
	$return_value='';
	if($redis->exists($main))$return_value = $redis->get($main);
	$return_value=\aw2_library::post_actions('all',$return_value,$atts);
	return $return_value;
}

\aw2_library::add_service('global_cache.exists','if exists in the global cache',['namespace'=>__NAMESPACE__]);
function exists($atts,$content=null,$shortcode=null){
	if(\aw2_library::pre_actions('all',$atts,$content)==false)return;
	
	extract( shortcode_atts( array(
	'main'=>null,
	'prefix'=>'',
	), $atts) );
	
	if(!$main)return 'Main must be set';		
	if($prefix)$main=$prefix . $main;
	//Connect to Redis and store the data
	$redis = \aw2_library::redis_connect(REDIS_DATABASE_GLOBAL_CACHE);
		
	$return_value=false;
	if($redis->exists($main))$return_value = true;
	$return_value=\aw2_library::post_actions('all',$return_value,$atts);
	return $return_value;
}


\aw2_library::add_service('global_cache.flush','Flush the Global Cache',['namespace'=>__NAMESPACE__]);

function flush($atts,$content=null,$shortcode){
	if(\aw2_library::pre_actions('all',$atts,$content,$shortcode)==false)return;
	//Connect to Redis and store the data
		$redis = \aw2_library::redis_connect(REDIS_DATABASE_GLOBAL_CACHE);
	$redis->flushdb() ;
}


\aw2_library::add_service('global_cache.del','Delete a Key',['namespace'=>__NAMESPACE__]);

function del($atts,$content=null,$shortcode){
	if(\aw2_library::pre_actions('all',$atts,$content,$shortcode)==false)return;
	extract( shortcode_atts( array(
	'main'=>null,
	'prefix'=>'',
	), $atts) );	
	if(!$main)return 'Main must be set';		
	if($prefix)$main=$prefix . $main;
	//Connect to Redis and store the data
	$redis = \aw2_library::redis_connect(REDIS_DATABASE_GLOBAL_CACHE);
	if($redis->exists($main))$redis->del($main);
	return;	
}


\aw2_library::add_service('global_cache.run','Set the Global Cache',['namespace'=>__NAMESPACE__]);

function run($atts,$content=null,$shortcode){
	if(\aw2_library::pre_actions('all',$atts,$content)==false)return;
	
	extract( shortcode_atts( array(
	'main'=>null,
	'ttl' => 30
	), $atts) );

	//Connect to Redis and store the data
	$redis = \aw2_library::redis_connect(REDIS_DATABASE_GLOBAL_CACHE);
		
	if($main && $redis->exists($main)){
		$return_value = $redis->get($main);
	}
	else{
		$return_value=\aw2_library::parse_shortcode($content) ;
		if($main && !(current_user_can('develop_for_awesomeui') && isset($_COOKIE['dev_no_cache']))){
			$redis->set($main, $return_value);
			$redis->setTimeout($main, $ttl*60);
		}
	}
		
	$return_value=\aw2_library::post_actions('all',$return_value,$atts);
	return $return_value;
}