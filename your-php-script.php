<?php 
   $dbName = "sqlsrv:Server=10.100.100.30;Database=beyond_web_data";

   $dbUser = "beyond_web_data";

   $dbPassword = "dsfaasdsAb^a12";
   $db = new PDO($dbName, $dbUser, $dbPassword);

   $appid = '535714f6f188ed2b88e287927a443d83';   //appid
   $appsecret = '10';
   $url = "https://apis.tianapi.com/ai/index?key=$appid&num=10";//请求地址
   //  2初始化curl请求
   $ch = curl_init();
   //3.配置请求参数
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
   curl_setopt($ch, CURLOPT_URL, $url);//请求
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不直接输出数据
   //4.开始请求
   $res = curl_exec($ch);//获取请求结果
   if (curl_errno($ch)) {
      var_dump(curl_error($ch));//打印错误信息
   }
   //5.关闭curl
   curl_close($ch);
   // echo json_encode($res, JSON_UNESCAPED_UNICODE).'<br>';

   $data = json_decode($res,JSON_UNESCAPED_UNICODE);

   // 获取 newslist 数组
   $newslist = $data['result']['newslist'];
   $sum=0;
   // 遍历 newslist 数组，获取每个新闻的标题
   foreach ($newslist as $news) {
      $title = $news['title'];
      $description=$news['description'];
      $time =date('Y-m-d', strtotime($news['ctime']));
      $time2=date('H:i:s', strtotime($news['ctime']));

      // echo "ctime：".$time.$time2."title".$title."description".$description;
      // echo '<br>';
      // echo $title."<br>";
      // echo $title.' '.$time.'<br>';
      $query=$db->query("SELECT * FROM news_ol_aikuaixun WHERE ai_title='$title'");
      $data=$query->fetch();
      if($data==''){
        echo $title.'<br>';
         $sql="INSERT INTO news_ol_aikuaixun (ai_title,ai_jianjie,ai_publisher,ai_source,ai_date1,ai_date2,ai_type)VALUES ('$title','$description', '人工智能','AI资讯','$time','$time2','1');";
         if ($db->exec($sql) ) {
            if($sum==0){
               $sum++;
               //echo "<script>alert('发布成功');window.parent.postMessage('tools', '*');</script>";
            }
         } else {
               //echo '<script>alert("添加失败")</script>';
         }
      }else{
         echo '空的<br>';
      }
   }
   // $sql="INSERT INTO news_ol_aiTools(tool_tit,tool_img,tool_url,tool_type,tool_jj,tool_you,ai_xh,ai_qy) VALUES('$tit','$img','$ai_url','$lx','$js','$ai_tz','$ai_xh','$ai_qy')";
   // echo  $sql;
  
?>
