<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
   // -- 更新排序 --
    for ($i=0; $i <count($_POST['OrderBy_hot']) ; $i++) { 
    $data=array("OrderBy_hot"=>$_POST['OrderBy_hot'][$i]);
    $where=array("Tb_index"=>$_POST['Tb_index'][$i]);
    pdo_update('appProduct', $data, $where);
  }
}

if ($_GET) {

   if (!empty($_GET['Tb_index'])) {//移除熱門商品

    $where=array('Tb_index'=>$_GET['Tb_index']);
    $param=array('HotPro'=>'0');

   	 pdo_update('appProduct',$param ,$where);
   }
   
   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT Tb_index, aTitle, aBrand, price, pro_num, HotPro, OrderBy_hot, OnLineOrNot, aPic FROM appProduct WHERE HotPro='1' AND webLang=:webLang  ORDER BY OrderBy_hot DESC, StartDate DESC");
   $sql->execute(array( "webLang"=>$weblang));
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

        <button id="sort_btn" type="button" class="btn btn-default">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button>

	    <!--<a href="manager.php?MT_id=<?php echo $_GET['MT_id'];?>">
        <button type="button" class="btn btn-default">
        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
        </a>-->
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>#</th>
								<th>產品圖示</th>
								<th>產品名稱</th>
								<th>品牌</th>
								<th>排序</th>
								<th>價格</th>
								<th>數量</th>
								
								<th>目前狀態</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php 
						  $i=1; 
						  while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
                           $aBrand=pdo_select("SELECT aTitle FROM appBrand WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['aBrand']]);
                           
					    ?>
							<tr>
								<td><?php echo $i?></td>
								<td><img src="../../img/<?php echo $row['aPic'];?>" style="width: 100px;"></td>
								<td><?php echo $row['aTitle'] ?></td>
								<td><?php echo $aBrand['aTitle']; ?></td>
								<td><input type="number" class="sort_in" name="OrderBy_hot" Tb_index="<?php echo $row['Tb_index'];?>" value="<?php echo $row['OrderBy_hot'] ?>"></td>
								<td>$<?php echo $row['price']?></td>
								<td><?php echo $row['pro_num']?></td>
								<td><?php echo $online= $row['OnLineOrNot']==1 ? '上線中' : '已下線';?></td>
								

								<td class="text-right">

								<a href="manager.php?MT_id=<?php echo $_GET['MT_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?MT_id=<?php echo $_GET['MT_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要移除 [<?php echo $row['aTitle']?>] 變為一般商品 ?')) {return false;}">
								<button type="button" class="btn btn-rounded btn-warning btn-sm">
								<i class="fa fa-trash" aria-hidden="true"></i>
								刪除</button>
								</a>

					
								</td>
							</tr>
						<?php $i++; }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#sort_btn").click(function(event) {

        var arr_OrderBy=new Array();
        var arr_Tb_index=new Array();

          $(".sort_in").each(function(index, el) {
             arr_OrderBy.push($(this).val());
             arr_Tb_index.push($(this).attr('Tb_index'));
          });
          
          var data={ 
                        OrderBy_hot: arr_OrderBy,
                       Tb_index: arr_Tb_index 
                      };
             ajax_in('admin.php', data, 'no', 'no');

          alert('更新排序');	
         location.replace('admin.php?MT_id=<?php echo $_GET['MT_id'];?>');
		});
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
