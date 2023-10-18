<?php
	//----------------------------------------------------------------------
	//	Function name	: sendEmail
	//	Parameter		:
	//			subject						= 메일 제목
	//			to_mail						= 받는사람 이메일 주소
	//			to_name						= 받는사람 이름
	//			from_mail					= 보내는 사람 이메일 주소
	//			from_name					= 보내는 사람 이름
	//			content						= 메일 내용
	//			to_cc_email				    = 받는사람 참조 이메일 주소
	//	Return			:
	//	Description		:
	//			메일 발송 함수
	//----------------------------------------------------------------------
	function sendEmail($subject, $to_email, $to_name, $from_email, $from_name, $content, $to_cc_email = "") {
		global $DEV_MODE;

		if ($DEV_MODE) {
			$to_email = "tjrwlslo@naver.com";
            $to_cc_email = "sj.yoon@raonworks.co.kr,tjrwlslo@kakao.com";
		}

		if (chkBlank($to_name)) {
			$to_config = $to_email;
		} else {
			//$to_config = "\"". $to_name . "\" <". $to_email .">";
			$to_config = $to_email;
		}

		if (chkBlank($from_name)) {
			$from_config = $from_email."\r\n";
		} else {
			$from_config = "From: ". $from_name ." <". $from_email .">\r\n";
		}

        //받는사람 CC
        if (!chkBlank($to_cc_email)) {
            $from_config .= "Cc: ". $to_cc_email ."\r\n";
        }

		$from_config.= "MIME-Version: 1.0\r\n";
		$from_config.= "Content-Type: text/html; charset=utf-8\r\n";
		$from_config.= "X-Mailer: PHP\r\n";


		$subject = '=?UTF-8?B?'.base64_encode( $subject ).'?=';
		mail($to_config, $subject, $content, $from_config);
	}