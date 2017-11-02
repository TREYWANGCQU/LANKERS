<?php
class user_QQ {
	public $appid  = '';
	public $appkey = '';
	public $scope  = "get_user_info,add_topic,add_one_blog,add_album,upload_pic,list_album,add_share,check_page_fans,do_like,get_tenpay_address,get_info,get_other_info,get_fanslist,get_idolist,add_idol";
	public $openid = '';
	public $url    = '';

	public function login(){
	    $state = md5(uniqid(rand(), TRUE)); //CSRF protection
	    iPHP::set_cookie("QQ_STATE",authcode($state,'ENCODE'));
	    $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code"
	        . "&client_id=" . $this->appid
	        . "&redirect_uri=" . urlencode($this->url)
	        . "&state=" .$state
	        . "&scope=".$this->scope;
	    header("Location:$login_url");
	}
	public function callback(){
		$state	= authcode(iPHP::get_cookie("QQ_STATE"), 'DECODE');

		if($_GET['state']!=$state && empty($_GET['code'])){
			$this->login();
			exit;
		}

        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
            . "client_id=" . $this->appid. "&redirect_uri=" . urlencode($this->url)
            . "&client_secret=" . $this->appkey. "&code=" . $_GET["code"];

        $response = $this->get_url_contents($token_url);

        if (strpos($response, "callback") !== false){
			$lpos     = strpos($response, "(");
			$rpos     = strrpos($response, ")");
			$response = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg      = json_decode($response);
            isset($msg->error) && $this->login();
        }
        $params = array();
        parse_str($response, $params);
		iPHP::set_cookie("QQ_ACCESS_TOKEN",authcode($params["access_token"],'ENCODE'));
        $this->access_token($params["access_token"]);
	}
	public function access_token($access_token=""){
		$access_token	= authcode(iPHP::get_cookie("QQ_ACCESS_TOKEN"), 'DECODE');
	    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$access_token;
	    $str  = $this->get_url_contents($graph_url);
	    if (strpos($str, "callback") !== false){
	        $lpos = strpos($str, "(");
	        $rpos = strrpos($str, ")");
	        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
	    }

	    $user = json_decode($str);
	    isset($user->error) && $this->login();

		$this->openid = $user->openid;
	    iPHP::set_cookie("QQ_OPENID",authcode($user->openid,'ENCODE'));
	}
	public function get_openid(){
		$this->openid  = authcode(iPHP::get_cookie("QQ_OPENID"), 'DECODE');
		return $this->openid;
	}
	public function get_user_info(){
		$this->openid  = authcode(iPHP::get_cookie("QQ_OPENID"), 'DECODE');
		$access_token  = authcode(iPHP::get_cookie("QQ_ACCESS_TOKEN"), 'DECODE');
		$get_user_info = "https://graph.qq.com/user/get_user_info?"
	        . "access_token=" . $access_token
	        . "&oauth_consumer_key=" .$this->appid
	        . "&openid=" .$this->openid
	        . "&format=json";

	    $info = $this->get_url_contents($get_user_info);
	    $arr = json_decode($info, true);
	    $arr['avatar']	= $arr['figureurl_2'];
	    $arr['gender']	= $arr['gender']=="??"?'1':0;
	    return $arr;
	}
	public function cleancookie(){
		iPHP::set_cookie('QQ_ACCESS_TOKEN', '',-31536000);
		iPHP::set_cookie('QQ_OPENID', '',-31536000);
		iPHP::set_cookie('QQ_STATE', '',-31536000);
	}
	public function get_url_contents($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response =  curl_exec($ch);
        curl_close($ch);
        //-------请求为空
        if(empty($response)){
            die("可能是服务器无法请求https协议</h2>可能未开启curl支持,请尝试开启curl支持，重启web服务器，如果问题仍未解决，请联系我们");
        }

	    return $response;
	}
}
