<?php
require 'inc/ojsettings.php';
require ('inc/checklogin.php');
$page='home';
if(isset($_GET['page'])){
	$page=$_GET['page'];
}

if(!isset($_SESSION['user'],$_SESSION['administrator'])){
  include '403.php';
}else if(!isset($_SESSION['admin_tfa']) || !$_SESSION['admin_tfa']){
  $_SESSION['admin_retpage'] = 'admin.php';
  header("Location: admin_auth.php?redirect=".$page);
  exit;
}else{
  require('inc/database.php');

  $res=mysqli_query($con,'select content from news where news_id=0');
  $index_text=($res && ($row=mysqli_fetch_row($res))) ? str_replace('<br>', "\n", $row[0]) : '';
  $res=mysqli_query($con,"select content from user_notes where id=0");
  $category=($res && ($row=mysqli_fetch_row($res))) ? str_replace('<br>', "\n", $row[0]) : '';

$inTitle='管理';
$Title=$inTitle .' - '. $oj_name;
?>
<!DOCTYPE html>
<html>
  <?php require('head.php'); ?>
  <body>
    <?php require('page_header.php'); ?>  
          
    <div class="container-fluid admin-page">
      <div class="row-fluid">
        <div class="span12">
          <div class="tabbable">
            <ul class="nav nav-pills" id="nav_tab" style="padding-right:10px;padding-left:10px;margin-right:15px;margin-left:-15px;lineheight:30px;font-size:20px">
              <li class="active"><a href="#home" data-toggle="tab">主页</a></li>
              <li class=""><a href="#news" data-toggle="tab">新闻</a></li>
              <li class=""><a href="#experience" data-toggle="tab">经验</a></li>
              <li class=""><a href="#user" data-toggle="tab">用户</a></li>
              <li class=""><a href="#others" data-toggle="tab">其它</a></li>
            </ul>
		  </div>
            <div class="tab-content">
              <div class="tab-pane fade in active" id="home">
                <div class="row-fluid">
                  <div class="span3 operations">
                    <h3 class="center">题目</h3>
                    <a href="newproblem.php" class="btn <?php echo $button_class?>">添加题目...</a>
					<a href="#" id="btn_category" class="btn <?php echo $button_class?>">题目分类...</a>
                    <a href="#" id="btn_rejudge" class="btn btn-info">重新评测...</a>
                  </div>
				  <hr class="visible-phone">
                  <div class="span5">
                    <h3 class="center">主页</h3><br>
                    <form action="#" method="post" id="form_index">
                      <input type="hidden" name="op" value="update_index">
                      <textarea name="text" rows="10" class="border-box" style="width:100%"><?php echo htmlspecialchars($index_text)?></textarea>
                      <span class="alert alert-success hide" id="alert_result">主页更新成功！</span>
                      <div class="pull-right">
                        <input type="submit" class="btn <?php echo $button_class?>" value="更新">
                      </div>
                    </form>
                  </div>
				  <hr class="visible-phone">
                  <div class="span4">
                    <h3 class="center" id="meter_title">系统信息</h3>
					<br>
                    <div id="cpumeter" class="meter"></div>
                    <div id="memmeter" class="meter"></div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="news">
                <div style="margin-left:50px;margin-right:50px">
				<button class="btn <?php echo $button_class?> pull-right" id="new_news">添加新闻...</button>
                  <div id="table_news">
                    <div class="row-fluid">
                      <div class="alert span4">正在加载新闻...</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="experience">
                <div style="margin-left:50px;margin-right:50px">
				<div class="row-fluid">
				<div class="span6">
                  <div id="table_experience_title"> 
                  </div>
                  <form action="admin.php" method="post" class="form-inline" id="form_experience_title">
                    <input type="text" id="input_experience" name="experience" class="input-medium" placeholder="经验值&nbsp;&ge;">&nbsp;&nbsp;
                    <input type="text" id="input_experience_title" name="title" class="input-medium" placeholder="头衔">&nbsp;&nbsp;
                    <input type="submit" class="btn" value="添加">
                    <input type="hidden" name="op" value="add_experience_title">
                  </form>
                 </div>
				 <hr class="visible-phone">
				 <div class="span6">
                  <form action="admin.php" method="post" id="form_level_experience">
                    <div id="table_level_experience"> 
                    </div>
                    <input type="submit" class="btn" value="更新">
                    <input type="hidden" name="op" value="update_level_experience">
                  </form>
                </div>
			  </div>
			  </div>
              </div>
              <div class="tab-pane fade" id="user">
                <div style="margin-left:50px">
				<div class="row-fluid">
				<div class="span6">
                  <div id="table_priv"></div>
                  <form action="admin.php" method="post" class="form-inline" id="form_priv">
                    <label for="input_user_id" style="display:block">添加权限</label>
                    <input type="text" id="input_user_id" name="user_id" class="input-medium" placeholder="用户名...">&nbsp;&nbsp;
                    <select class="input-medium" name="right" id="slt_right">
                      <option value="administrator">管理人员</option>
                      <option value="source_browser">代码审核</option>
                      <option value="insider">隐藏可见</option>
                    </select>&nbsp;&nbsp;
                    <input type="submit" class="btn" value="添加">
                    <input type="hidden" name="op" value="add_priv">
                  </form>
				</div>
				<hr class="visible-phone">
				<div class="span6">
				<div id="table_usr"></div>
                  <form action="admin.php" method="post" class="form-inline" id="form_usr">
                    <label for="input_dis_usr" style="display:block">禁用某个用户</label>
                    <input type="text" id="input_dis_usr" name="user_id" class="input-medium" placeholder="用户名...">&nbsp;&nbsp;
                    <input type="submit" class="btn" value="禁用">
                    <input type="hidden" name="op" value="disable_usr">
                  </form>
				</div>
				</div>  
                </div>
              </div>
              <div class="tab-pane fade" id="others">
			  <div style="margin-left:50px;margin-right:50px">
			    <div class="row-fluid">
                  <div class="span12">
				  <h3>更新<?php echo $oj_name?>（临时废弃）</h3>
				  <br>
				  <div style="font-size:16px">
				    当前版本: <?php echo"{$web_ver}"?>
					<div style="margin-top:20px;height:30px">
						<span class="alert alert-info" id="updstatus">正在查找更新...</span>
					</div>
				    <div id="div_updfound" class="hide" style="margin-top:10px;margin-buttom:10px">
						<input type="button" id="btn_updnow" class="btn <?php echo $button_class?>" value="安装更新..."/>&nbsp;&nbsp;&nbsp;
                        <a href="https://github.com/CDFLS/CWOJ/commit" id="btn_updlog">更新日志</a>
                    </div>
				  </div>
                  </div>
				</div>
              </div>
			  </div>
            </div>
          </div>
        </div>
	  
	<div class="modal fade hide" id="CategoryModal">
      <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>题目分类编辑</h4>
      </div>
      <form class="margin-0" method="post" id="category_submit">
	    <p></p>
        <div class="modal-body" style="padding-top:5px">
		  <textarea style="box-sizing: border-box;width:100%;resize:none" id="input_category" rows="16" name="source" placeholder="请输入显示在首页的题目分类列表代码..."><?php echo $category?></textarea>
          <div class="alert alert-error hide margin-0" id="addcategory_res">发生错误</div>
        </div>
        <div class="modal-footer form-inline">
          <button class="btn btn-primary" id="addcategory_submit">提交</button>
          <a href="#" class="btn" data-dismiss="modal">关闭</a>
        </div>
		<div class="hidden-phone" style="width:750px"></div>
      </form>
    </div>
	 
	  <div class="modal fade hide" id="RejudgeModal">
      <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>重新评测</h4>
      </div>
      <form class="margin-0" method="post" id="rejudge_num">
        <div class="modal-body">
		    <label class="control-label">请输入需要重新评测的题号:</label>
	        <input class="input-xlarge" id="input_rejudge" type="number" placeholder="1000~9999">
			<div class="alert hide" id="rejudge_res" style="margin-top:20px"></div>
        </div>
        <div class="modal-footer form-inline">
          <div class="pull-left">
          </div>
		  <button class="btn btn-primary" id="rejudge_submit">重新评测</button>
          <a href="#" class="btn" data-dismiss="modal">关闭</a>
        </div>
      </form>
    </div>
	
	<div class="modal fade hide" id="NewsModal">
      <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><span class="hide" id="NewsModalTitle"></span></h4>
      </div>
      <form class="margin-0" method="post" id="news_submit">
	    <p></p>
        <div class="modal-body" style="padding-top:5px">
		  <input type="text" id="input_newstitle" name="news" class="input-xlarge" placeholder="请输入新闻标题...">
		  <textarea style="box-sizing: border-box;width:100%;resize:none" id="input_newscontent" rows="14" name="source" placeholder="请输入新闻内容 (可选)..."></textarea>
          <div class="alert alert-error hide margin-0" id="addnews_res">发生错误</div>
        </div>
        <div class="modal-footer form-inline">
		  <button class="pull-left btn btn-danger hide" id="btn_delnews">删除</button>
		  <button class="pull-left btn btn-info" id="btn_upload">上传图片...</button>
       <label class="checkbox" style="padding-right:10px">
       <input type="checkbox" name="is_top" id="is_top">顶置新闻
       </label>
       <button class="btn btn-primary" id="addnews_submit">提交</button>
		  <button class="btn btn-primary hide" id="editnews_submit">提交</button>
          <a href="#" class="btn" data-dismiss="modal">关闭</a>
        </div>
		<div class="hidden-phone" style="width:750px"></div>
      </form>
    </div>
      <hr>
      <footer>
        <p>&copy; <?php echo"{$year} {$oj_copy}";?></p>
      </footer>
    </div>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/common.js"></script>
    <script src="/assets/js/highcharts.js"></script>
    <script src="/assets/js/highcharts-more.js"></script>

    <script type="text/javascript">
	var loffset=window.screenLeft+200;
    var toffset=window.screenTop+200;
	var getlevellist=function(){$('#table_level_experience').load('ajax_admin.php',{op:'list_level_experience'});};
    var gettitlelist=function(){$('#table_experience_title').load('ajax_admin.php',{op:'list_experience_title'});};
    var getprivlist=function(){$('#table_priv').load('ajax_admin.php',{op:'list_priv'});};
    var getnewslist=function(){$('#table_news').load('ajax_admin.php',{op:'list_news'});};
    var getusrlist=function(){$('#table_usr').load('ajax_admin.php',{op:'list_usr'});};
	var cnt=-1;
	var newver=0;
	function chkupd(){
		$.ajax({
			type:"POST",
			url:"ajax_update.php",
			data:{"type":'check'},
			success:function(msg){
				if(msg=='false') {
					$('#updstatus').html('=.= 并没有找到更新...');
				}
				else if(msg=='error'){
					$('#updstatus').removeClass('alert-info').addClass('alert-danger');
					$('#updstatus').html('连接超时，请刷新页面重试...');
				}
				else {
					newver = msg;
					$('#updstatus').removeClass('alert-info').addClass('alert-success');
					$('#updstatus').html('发现更新的版本: '+msg);
					$('#div_updfound').show();
					};
				}
				});
		}
		chkupd();
      $(document).ready(function(){
		var page='<?php echo $page?>';
		if(page=='news'){
			$('#nav_tab a[href="#news"]').tab('show');
			getnewslist();
		}
		else if(page=='experience'){
			$('#nav_tab a[href="#experience"]').tab('show');
			getlevellist();
			gettitlelist();
		} 
		else if(page=='user'){
			$('#nav_tab a[href="#user"]').tab('show');
			getprivlist();
			getusrlist();
		}
		else if(page=='others') $('#nav_tab a[href="#others"]').tab('show');
		else $('#nav_tab a[href="#home"]').tab('show');
		$('#btn_updnow').click(function(){
			btn_updnow.setAttribute("disabled", true); 
			btn_updnow.value = "正在下载...";
			$.ajax({
			async: true,
			type:"POST",
			url:"ajax_update.php",
			data:{"type":'getfile', "newver": newver},
			success:function(msg){
				if (msg == 'success') {
					btn_updnow.value = "正在安装...";
					$.ajax({
						async: true,
						type:"POST",
						url:"ajax_update.php",
						data:{"type":'install', "newver": newver},
						success:function(msg){
							if(msg == 'success'){
								$('#div_updfound').hide();
								$('#updstatus').html('成功安装更新，页面即将刷新...');
								window.setTimeout("window.location='admin.php?page=others'",3000); 
							}
						}
					});
				}	
				else alert(msg);
				}
			});
		});
		$('#new_news').click(function(){
			$('#NewsModalTitle').html('添加新闻').show();
       $('#NewsModal').modal('show');
			$('#input_newstitle').val("");
		    $('#input_newscontent').val("");
			$('#addnews_submit').show();
		    $('#editnews_submit').hide();
			$('#btn_delnews').hide();
       document.getElementById("is_top").checked = false;
		});
        $('#ret_url').val("admin.php");
		$('#btn_rejudge').click(function(){
			$('#RejudgeModal').modal('show');
		});
		$('#btn_category').click(function(){
			$('#CategoryModal').modal('show');
		});
		$('#addcategory_submit').click(function(){
			$('#addcategory_res').hide();
			$.ajax({
					type:"POST",
					url:"ajax_admin.php",
					data:{"op":'update_category',"content":$.trim($('#input_category').val())},
					success:function(msg){
						if(msg=='success') $('#CategoryModal').modal('hide');
						else $('#addcategory_res').show();
					}
				});
			return false;
		});
		$('#addnews_submit').click(function(){
			var title,content;
			$('#addnews_res').hide();
			var a=false;
			if(!$.trim($('#input_newstitle').val())) {
            $('#input_newstitle').addClass('error');
            a=true;
            }else{
            $('#input_newstitle').removeClass('error');
            }
			if(!a){
				var importance=0;
				if (document.getElementById('is_top').checked) 
                    importance=1;
				$.ajax({
					type:"POST",
					url:"ajax_admin.php",
					data:{"op":'add_news',"title":$.trim($('#input_newstitle').val()),"content":$.trim($('#input_newscontent').val()),"importance":importance},
					success:function(msg){
						if(msg=='success') $('#NewsModal').modal('hide');
						else $('#addnews_res').show();
					}
					});
			}
			getnewslist();
			return false;
		});
        $('#rejudge_submit').click(function(){
          var obj=$('#rejudge_res').hide();
          var id=$.trim($('#input_rejudge').val());
          if(id!=null){
            id=$.trim(id);
            if(id){
              $.get("rejudge.php?problem_id="+id,function(msg){
				  if(msg=='success'){
					  $('#RejudgeModal').modal('hide');
				  }else{
					  obj.addClass('alert-error');
					  obj.html(msg).slideDown();
				  }
              });
            }
          }
		  return false;
        });
        $('#nav_tab').click(function(E){
          var jq=$(E.target);
          if(jq.is('a')){
            if(E.target.innerHTML.search(/新闻/i)!=-1)
              getnewslist();
            else if(E.target.innerHTML.search(/用户/i)!=-1){
              getusrlist();
		      getprivlist();
			}
            else if(E.target.innerHTML.search(/经验/i)!=-1){
              getlevellist();
              gettitlelist();
            }
          }
        });
        $('#table_experience_title').click(function(E){
          E.preventDefault()
          var $i=$(E.target);
          if($i.is('i.icon-remove')){
            var id=$i.data('id');
            $.post('ajax_admin.php',{'op':'del_title','id':id},function(){
              gettitlelist();
            })
          }
        });
        $('#form_experience_title').submit(function(E){
          E.preventDefault();
          $.ajax({
            type:"POST",
            url:"ajax_admin.php",
            data:$(this).serialize(),
            success:gettitlelist
          });
        });
        $('#form_level_experience').submit(function(E){
          E.preventDefault();
          $.ajax({
            type:"POST",
            url:"ajax_admin.php",
            data:$(this).serialize(),
            success:getlevellist
          });
        });
        $('#table_usr').click(function(E){
          E.preventDefault();
          var jq=$(E.target);
          if(jq.is('i')){
            var oper;
            var str_id=jq.parents('tr').first().children().first().contents()
              .filter(function(){return this.nodeType == 3;})
              .text();
            if(jq.hasClass('icon-remove')){
              oper='del_usr';
            }else{
              oper='en_usr';
            }
            $.ajax({
              type:"POST",
              url:"ajax_admin.php",
              data:{
                op:oper,
                user_id:str_id
              },
              success:getusrlist
            });
          }
          return false;
        });
        $('#table_priv').click(function(E){
          E.preventDefault();
          var jq=$(E.target);
          if(jq.is('i')){
            var jq_pri=jq.parent().parent().prev();
            var jq_uid=jq_pri.prev();
            $.ajax({
              type:"POST",
              url:"ajax_admin.php",
              data:{
                op:'del_priv',
                user_id:jq_uid.html(),
                right:jq_pri.html()
              },
              success:getprivlist
            });
          }
          return false;
        });
        $('#form_usr').submit(function(E){
          E.preventDefault();
          $.ajax({
            type:"POST",
            url:"ajax_admin.php",
            data:$('#form_usr').serialize(),
            success:getusrlist
          });
          return false;
        });
        $('#form_priv').submit(function(E){
          E.preventDefault();
          $.ajax({
            type:"POST",
            url:"ajax_admin.php",
            data:$('#form_priv').serialize(),
            success:getprivlist
          });
          return false;
        });
		$('#table_news').click(function(E){
		 var news_title,news_content;
		 E.preventDefault();
		 var jq=$(E.target);
          if(jq.is('i')){
            var jq_id=jq.parent().parent().prev().prev().prev();
			cnt = jq_id.html();
			$.ajax({
              type:"POST",
              url:"ajax_admin.php",
              data:{
                op:'get_news',
                news_id:jq_id.html()
              },
              success:function(data){
                  var obj=eval("("+data+")");
				  $('#NewsModalTitle').html('编辑新闻').show();
		          $('#NewsModal').modal('show');  
				  if(obj.importance=='1') document.getElementById("is_top").checked = true;
				else document.getElementById("is_top").checked = false;
				  $('#addnews_submit').hide();
				  $('#editnews_submit').show();
				  $('#btn_delnews').show();
		          $('#input_newstitle').val(obj.title);
				  $('#input_newscontent').val(obj.content);
				  }
            });
		  }
         return false;
        });
        $('#editnews_submit').click(function(){
		  var title,content;
			$('#addnews_res').hide();
			var a=false;
			if(!$.trim($('#input_newstitle').val())) {
            $('#input_newstitle').addClass('error');
            a=true;
            }else{
            $('#input_newstitle').removeClass('error');
            }
			if(!a){
				var importance=0;
				if (document.getElementById('is_top').checked) 
                    importance=1;
				$.ajax({
					type:"POST",
					url:"ajax_admin.php",
					data:{"op":'edit_news',"news_id":cnt,"title":$.trim($('#input_newstitle').val()),"content":$.trim($('#input_newscontent').val()),"importance":importance},
					success:function(msg){
						if(msg=='success') $('#NewsModal').modal('hide');
						else $('#addnews_res').show();
					}
					});
			}
			getnewslist();
			return false;
        });
		$('#btn_delnews').click(function(){
			$.ajax({
              type:"POST",
              url:"ajax_admin.php",
              data:{
                "op":'del_news',
                "news_id":cnt
              },
              success:function(msg){
				  if(msg=='success') $('#NewsModal').modal('hide');
				  else $('#addnews_res').show();
			  }
            });
			getnewslist();
			return false;
		});
		$('#btn_upload').click(function(){
			window.open("upload.php",'upload_win2','left='+loffset+',top='+toffset+',width=400,height=300,toolbar=no,resizable=no,menubar=no,location=no,status=no');
			return false;
		});
        $('#form_index').submit(function(E){
          E.preventDefault();
          $('#alert_result').hide();
          $.ajax({
            type:"POST",
            url:"ajax_admin.php",
            data:$('#form_index').serialize(),
            success:function(msg){
              if(/success/.test(msg))
                $('#alert_result').show();
              else{
                $('#alert_result').removeClass("alert-success");
                $('#alert_result').addClass("alert-danger");
                $('#alert_result').html('主页更新失败...').show();
               }
            }
          });
          return false;
        });
        $('#input_adminpass').focus();
      });

      function update_chart(){
        $.getJSON('ajax_usage.php',function(data){
          // console.log(data);
          if(data&&"number"==typeof(data.cpu)){
            if(!window.cpuChart){
              window.cpuChart = new Highcharts.Chart({
                chart: {
                  renderTo: 'cpumeter'
                },        
                yAxis: [{
                  title: {
                    text: 'CPU'
                  }
                }],
                series: [{
                  data: [0],
                  yAxis: 0
                }]
              });
            }
            cpuChart.series[0].points[0].update(data.cpu,true);
          }
          if(data&&"number"==typeof(data.mem)){
            if(!window.memChart){
              window.memChart = new Highcharts.Chart({
                chart: {
                  renderTo: 'memmeter'
                },        
                yAxis: [{
                  title: {
                    text: 'RAM'
                  }
                }],
                series: [{
                  data: [0],
                  yAxis: 0
                }]
              });

              $('#meter_title').show();
            }
            memChart.series[0].points[0].update(data.mem,true);
          }

          setTimeout('update_chart()',3000);
        });
      }
      $(function () {
        Highcharts.setOptions({
          chart: {
            type: 'gauge',
            plotBorderWidth: 1,
            plotBackgroundColor: {
              linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
              stops: [
                [0, '#FFF9D9'],
                [0.2, '#FFFFFF'],
                [1, '#FFF4C6']
              ]
            },
            plotBackgroundImage: null,
            height: 150
          },
          credits: {
            enabled: false
          },

          title: {
            text: null//'VU meter'
          },
          
          pane: [{
            startAngle: -45,
            endAngle: 45,
            background: null,
            center: ['50%', '145%'],
            size: 260
          }],                 
        
          yAxis: [{
            min: 0,
            max: 100,
            tickInterval: 25,
            minorTickPosition: 'outside',
            tickPosition: 'outside',
            labels: {
              rotation: 'auto',
              distance: 20,
              formatter: function() {
                return this.value + '%';
              }
            },
            plotBands: [{
              from: 70,
              to: 100,
              color: '#C02316',
              innerRadius: '100%',
              outerRadius: '105%'
            }],
            pane: 0,
            title: {
              // text: 'Memory',
              y: -40
            }
          }],
          plotOptions: {
            gauge: {
              animation: false,
              dataLabels: {
                enabled: false
              },
              dial: {
                radius: '100%'
              }
            }
          },
          series: [{
            data: [0],
            yAxis: 0
          }]
        });

        update_chart();
      });
    </script>
  </body>
</html>
<?php }?>