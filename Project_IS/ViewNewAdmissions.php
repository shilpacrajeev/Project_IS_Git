<!DOCTYPE HTML>
<?php 
	session_start();
    if(!$_SESSION["name"]) 
	  	header("Location: Login.html");
?>
<html>
<head>
			<title>I-Tec</title>
			<link href="css/style.css" rel="stylesheet" type="text/css">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
</head>
<body>
			<div class="header_bg"><label class="user">Hi, <?php echo $_SESSION["name"];?></label>
						<div class="wrap"> 
							<div class="margin_top">
									<div class="top_menu_region">
										<div class="top_menu_style">
									     	<div class="menu">
									     	<nav>
									     		<ul>
											    	<li class="active"><a href="AdminHome.php">Home</a></li>
													<li><a href="ContactUs.html">Contact Us</a></li>
													<li><a href="Logout.php">Logout</a></li>
											    	
								     			</ul>
								     			</nav>									
												<div class="clear"></div>
									     	</div>
									     	<div class="clear"></div>
							     	</div>
								     <div class="header-bottom">
										 <div class="logo">
											<h1><a href="index.html">I-Tec</a></h1>
											<h6> An Integrated Solution</h6>
										 </div>
										<div class="clear"></div> 
									</div>
								</div>	
							</div>
						 </div>
			</div>
			<div class="pink_shade">
				<div class="wrap">
					<div class="main_body">
						<div class="main_image">	
								<img id="slide" src="images/banner5.jpg" alt="" width="1190px" height="250px">
							<div class="main_body_text">
									<div class="col_1_of_2 span_1_of_2">
										<nav>
												<ul class="sidebar">
														<li class="vert"><a href="#">Course</a></li>
																<ul class="sidebar line">
																			<li class="sub_vertical_menu"><a href="#">View All Courses</a></li>
																</ul>
														<br><br>
														<li class="vert"><a href="#">Students</a></li>
																<ul class="sidebar line">
																			<li class="sub_vertical_menu"><a href="#">View Enrolled Students</a></li>
																			<li class="sub_vertical_menu"><a href="ViewNewAdmissions.php">View New Admissions</a></li>
																			<li class="sub_vertical_menu"><a href="ViewStudentProfile.php">View Student Profile</a></li>
																</ul>
														<br><br>
														<li class="vert"><a href="#">Mark Management</a></li>
																<ul class="sidebar line">
																			<li class="sub_vertical_menu"><a href="#">Upload Marks</a></li>
																			<li class="sub_vertical_menu"><a href="#">View Marks</a></li>
																			<li class="sub_vertical_menu"><a href="#">Edit Marks</a></li>
																</ul>
														<br><br>
														<li class="vert"><a href="#">Inbox</a></li>
														
													</ul>
										</nav>
										<div class="clear"> </div>
									</div>	
									<div class="col_2_of_2 span_2_of_2">
												<div class="title-img1">
													<div class="title"><img src="images/book.png" alt=""/></div>
														<div class="title-desc"><h6>Student Admissions</h6></div>
														<div class="clear"></div> 
												</div>					
												<p class="desc-middle"><strong> New Admissions </strong></p>
												<p class="desc1"> </p>
												<?php
																
																			// Connection to both database
																			// Database Connection to Fedena
																			$db_f = new mysqli("localhost","root","mysqlshilpa","fedena");
																			// Checking the connection
																			if($db_f->connect_errno > 0)
																			{
																				echo "<br>Failed Connection to Fedena";
																			}
																			
																			// Database Connection to Moodle
																			$db_m = new mysqli("localhost","root","mysqlshilpa","moodles");
																			// Checking the connection
																			if($db_m->connect_errno > 0)
																			{
																				die("Failed Connection to Mooodle");
																			}
																			
																			// Locking the tables
																			/* Locking users table in Fedena*/
																			$query_f="lock tables users write";
																			if($db_f->query($query_f))
																			{
																				/* Locking mdl_user and mdl_role_assignment tables in moodle */
																				$query_m="lock tables mdl_user write,mdl_role_assignments write, mdl_user_preferences write";
																				if($db_m->query($query_m))
																				{
																					// selecting newly admitted student from fedena.
																					/* New admissions won't have LMS account activated . 
																					 * In that case lms_map field of users table in Fedena will hve a value 0
																					 * So new students will have lms_map = 0
																					 */
																					
																					/*// New Students are displayed
																					$query_f="select * from users where lms_map=0 and student = 1 LIMIT 3";
																					$export=$db_f->query($query_f);	
																					while($row = $export->fetch_assoc())
																					{
																						echo "<br>". $row["first_name"]." ---- ".$row["last_name"]."<br>";
																					
																					}*/
																					mysqli_autocommit($db_f,false);
																					mysqli_autocommit($db_m,false);
																					$query_f="select * from users where lms_map=0 and student = 1 LIMIT 3";
																					$export=$db_f->query($query_f);	
																					$no_new_adms=mysqli_num_rows($export);
																					if($no_new_adms<1)
																					{
																						echo "No new admissions";
																					}
																					else 
																					{
																							while($row = $export->fetch_assoc())
																							{
																									$flag=0;
																									$name=$row["first_name"];
																									$name=str_replace(' ', '', $name);
																									$last=$row["last_name"];
																									$username=''.$name.'_'.$next_id;
																									$username= strtolower($username);
																									$username=str_replace(' ', '', $username);
																									$password=''.crypt($username,'$2y$10$upBpwYVt.WI66VuCBO7rFueWDR8RDfhtdh');
																									$query="select max(id) from mdl_user";
																									$res_m=$db_m->query($query);
																									$next_id=$row1_m[0];
																									$email='abc_'.$next_id.'@gmail.com';
																									$i="insert into mdl_user(username,password,firstname,lastname,email,lang,calendartype,firstaccess,lastaccess,lastlogin,currentlogin,lastip,timecreated,timemodified,confirmed,policyagreed,mnethostid) values('".$username."','".$password."','".$name."','".$last."','".$email."','en','gregorian',0,0,0,0,'127.0.0.1',".time().",".time().",0,0,1);";
																									if($db_m->query($i))
																									{
																										$flag=1;
																										$query="select max(id) from mdl_user";
																										$res_m=$db_m->query($query);
																										$row1_m=$res_m->fetch_array();
																										$next_id=$row1_m[0];
																										$i2="insert into mdl_user_preferences (userid,name,value) values(".$next_id.",'htmleditor',0);";
																										if($db_m->query($i2))
																										{
																											$i2="insert into mdl_user_preferences (userid,name,value) values(".$next_id.",'auth_forcepasswordchange',0);";
																											if($db_m->query($i2))
																											{
																												$i2="insert into mdl_user_preferences (userid,name,value) values(".$next_id.",'email_bounce_count',1);";
																											if($db_m->query($i2))
																											{	
																												$i2="insert into mdl_user_preferences (userid,name,value) values(".$next_id.",'email_send_count',1);";
																												if($db_m->query($i2))
																												{
																													$i2="insert into mdl_role_assignments (roleid,contextid,userid,timemodified,modifierid) values(5,2,'".$next_id."','".time()."',2);";
																													if($db_m->query($i2))
																													{
																														$flag=2;
																														$f="update users set lms_map=".$next_id." where id=".$row['id'].";";
																														if($db_f->query($f))
																														{
																															$flag=3;
																															mysqli_commit($db_f);
																															mysqli_commit($db_m);
																															$next_id=$next_id+1;
																															
																															echo "<table><tr><td>".$row["first_name"]."</td><td>".$row["last_name"]."</td></tr></table>";
																																	
																															}
																														}
																														
																														else
																														{
																															if(flag!=3)
																															{
																																mysqli_rollback($db_f);
																																mysqli_rollback($db_m);
																															}
																															die(mysqli_error($db_f));
																														}
																												}
																												else
																												{
																													echo "<br> Failed preferences1;";
																												}
																												}
																												else
																												{
																													echo "<br> Failed preferences2;";
																												}
																												}
																												else
																												{
																													echo "<br> Failed preferences3;";
																												}
																												}
																												else
																												{
																													ini_set("display_startup_errors", "1");
																													ini_set("display_errors", "1");
																													error_reporting(E_ALL);
																													echo "<br> Failed preferences4;".mysqli_error($db_m);
																												}
																													}
																													else 
																													{
																														if(flag!=3)
																														{
																															mysqli_rollback($db_f);
																															mysqli_rollback($db_m);
																														}
																														die(mysqli_error($db_m));
																													}
																									}
																									if(flag!=3)
																									{
																										mysqli_rollback($db_f);
																										mysqli_rollback($db_m);																											
																									}
																									die(mysqli_error($db_m));
																								
																							}
																					
																				}
																				else
																				{
																					echo "<br> Lock on mdl_user and mdl_role_assignments failed:".mysqli_error($db_m);
																				}
																			}	
																			else
																			{
																				echo "<br> Lock failed on users of fedena:".mysqli_error($db_m);
																			}
																			$query="unlock tables";
																			$res=$db_f->query($query);
																			$res=$db_m->query($query);
																			
													?>


												
									</div>									
									<div class="clear"></div> 
							</div>					
						</div>
					</div>
				</div>					
				<div class="clear"></div> 
			</div>
			<div class="pink_shade">	
				<div class="wrap">
		  			<div class="designer">
							<p>Design by Shilpa.C</p>
					</div>
				</div>
			</div>
</body>
</html>
