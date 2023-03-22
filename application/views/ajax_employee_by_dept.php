

<?php if(count($todays_users)==0){
		echo '<div class="alert alert-danger" role="alert">
				none!
		</div>';
	 }
else { ?>
	
		<?php
		$i = $offset;
		foreach ($todays_users as $user){ 
		?>
			<tr>
				<td class="hidden-xs"><?php echo ++$i; ?></td>
				<td class="hidden-xs"><?php echo $user['emp_id']; ?></td>
				<td><span class="hidden-xs"><a
						href="<?php echo base_url();?>user/detail/<?php echo $user['emp_id']; ?>"><?php echo $user['name']; ?></a></span>
					<div class="visible-xs">
						<table>
							<tr>
								<td><b>ID</b></td>
								<td width='10'><b>:</b></td>
								<td><?php echo $user['emp_id']; ?></td>
							</tr>
							<tr>
								<td><b>Name</b></td>
								<td><b>:</b></td>
								<td><a
									href="<?php echo base_url();?>user/detail/<?php echo $user['emp_id']; ?>"><?php echo $user['name']; ?></a></td>
							</tr>
							<tr>
								<td><b>Department</b></td>
								<td><b>:</b></td>
								<td><?php echo $all_dept[$user['dept_code']]; ?></td>
							</tr>
							<tr>
								<td><b>Designation</b></td>
								<td><b>:</b></td>
								<td><?php echo $all_dept[$user['designation']]; ?></td>
							</tr>
							<tr>
								<td><b>In time</b></td>
								<td><b>:</b></td>
								<td><?php echo $user['status']; ?></td>
							</tr>
							<!-- <tr>
								<td><b>Out time</b></td>
								<td><b>:</b></td>
								<td><?php echo $user['jdate']; ?></td>
							</tr> -->
						</table>
					</div></td>
				<td class="hidden-xs"><?php echo $all_dept[$user['dept_code']]; ?></td>
				<td class="hidden-xs"><?php echo $designations[$user['designation']]; ?></td>
				<td class="hidden-xs"><?php echo $user['office_stime']; ?></td>
				<td class="hidden-xs">
					<?php
						$class = "highlighted-green";
						if ( 
							(strtotime($user['office_stime']) == strtotime('09.00.00') &&  strtotime("+15 minutes", strtotime($user['office_stime'])) < strtotime($user['stime']))
							|| (strtotime($user['office_stime']) == strtotime('10.00.00') &&  strtotime("+59 seconds", strtotime($user['office_stime'])) < strtotime($user['stime']) )
							)
						{
							$class = "highlighted-red";
						}
					?>
					<span class="<?php echo $class; ?>"><?php echo date("g:i a", strtotime($user['stime'])) ?></span>
				</td>
				<!-- <td class="hidden-xs"><?php echo $user['jdate']; ?></td> -->
			</tr>
			<?php
			}
			
			?>
	
<?php }?>

<script>
// $(function(){
// 	$("#select_dept").change(function(){
// 		var dept_code = $(this).val();
// 		// alert('hello ' +dept_code);
		
// 		$.ajax({
// 			type: "POST",
// 			url: '<?=base_url();?>attendance/attendance_by_dept/"',
// 			dataType: 'text',
// 			data: { dept_code: dept_code },
// 			success: function(data){
// 				$('#changing_table').replaceWith(data);
// 				// alert(data);
// 				// console.log(data);
// 				// console.log(data.content);
// 			}
			
// 		});
// 	});

// });
$('#pagination').hide();
// $('#seeAll').CSS('display','block');
$('#seeAll').show();

</script>
