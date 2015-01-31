<!DOCTYPE HTML>
<?php
		/* 
		 * ------------- Code to check the validity of Login Information ---------------------
		 */
		/* 
		 * Validating Login Information by connecting to LMS Account - Moodle 
		 * Steps followed are
		 * 1.Connect to corresponding database
		 * 2.Fetch salt value of corresponding username if it exists
		 * 3.Use crypt() to check the validity of password. 
		 * Moodle password is encrypted as crypt(actual_password,salt of 30 chars)
		 * 4.Make successful login if username and password combination are valid
		 * 
		 */
		if(isset($_POST["submit"]))
		{
					// Database Connection
					$db_lms = new mysqli("localhost","root","mysqlshilpa","moodles");
					// Checking the connection object
					if($db_lms->connect_errno > 0)
					{
						die("<font color=red>! Error: Failed Connection to Server Database. </font>");
					}
					else
					{
						/*
						 * Successful Connection to databse
						 */
						/*
						 * Salt for password comparison is stored along with password in database.
						 * So, get password from database
						 */
						$result_lms=$db_lms->query("select password,id from mdl_user where username='".$_POST["username"]."' and deleted=0 and confirmed=1;");
						if(mysqli_num_rows($result_lms)==1)
						{
							$row_lms = mysqli_fetch_array($result_lms);
							/*
							 * Use the first 30 characaters of fetched password as salt and give the salt with 
							 * input password for hashing.
							 * If hashed input password matches with password in database then login should be successful.
							 */
							$hash_input_password=crypt($_POST["password"],substr($row_lms['password'],0,30));
							if(strcmp($hash_input_password,$row_lms)==0)
							{
								echo "Success";
								/*
								 * Based on role redirect the user to the corresponding homepage
								 */
								$id=$row_lms['id'];
								$result_lms=$db_lms->query("select roleid from mdl_role_assignments where userid=".$id." and contextid=2;");
								if(mysqli_num_rows($result_lms)==1)
								{									
									$row_lms=mysqli_fetch_array($result_lms);
									$roleid=$row_lms['roleid'];
									echo "Role:".$roleid;
									$result_lms=$db_lms->query("select shortname from mdl_role where id=".$roleid.";");
									if(mysqli_num_rows($result_lms)==1)
									{
										$row_lms=mysqli_fetch_array($result_lms);
										$rolename=$row_lms['shortname'];
										if((strcmp($rolename,"editingteacher")==0)||(strcmp($row_lms['shortname'], "teacher")==0))
										{
											session_start();
											session_regenerate_id(true);
											$_SESSION["userid"]=$id;
											$_SESSION["username"]=$_POST["username"];
											$_SESSION["id"]=session_id();
											header("Location: TeacherHome.php");
										}
										else 
										{
											if(strcmp($row_lms['shortname'],"student")==0)
											{
												session_start();
												session_regenerate_id(true);
												$_SESSION["userid"]=$id;
												$_SESSION["username"]=$_POST["username"];
												$_SESSION["id"]=session_id();
												header("Location: StudentHome.php");
											}
										}
									}
									else
									{
										die("<font color=red> User Account's Role Not Authorized by LMS Admin </font>".mysqli_error($db_lms));
									}
								}
								else
								{
									/* 
									 * Role of LMS admin does not exist in mdl_role_assignments.
									 * Instead this info is present in mdl_config table
									 * So fetch data from this table and identifies where the siteadmin's
									 * username and password is the input. 
									 */ 
									$result_lms=$db_lms->query("select value from mdl_config where id=19 and name='siteadmins';");
									if(mysqli_num_rows($result_lms)==1)
									{
										$row_lms=mysqli_fetch_array($result_lms);
										/*
										 * Multiple admin if exist then the value will be store in LMS as
										 * id1,id2,id3 etc. ie. comma seperated ids.
										 * So to compare those id to id of current user, we split the id into array named
										 * $split. Then, each array element is compared with current user's id										 * 										
										 */
										$split=explode(',', $row_lms[0]);
										$i=0;
										while(isset($split[$i]))
										{
												if($id==$split[$i])
												{
													session_start();
													session_regenerate_id(true);
													$_SESSION["userid"]=$id;
													$_SESSION["username"]=$_POST["username"];
													$_SESSION["id"]=session_id();
													header("Location: AdminHome.php");
												}
										}
										die("<font color=red> User Account Not Authorized by LMS Admin </font>");									
									}
									else
									{
										die("<font color=red> User Account Not Authorized by LMS Admin </font>");
									}										
								}								
							}
							else
							{
								die("<font color=red> Invalid Username Or Password. </font>");
							}
						}	
						else 
						{
									die("<font color=red> Invalid Username Or Password. </font>");
						}
					}
		}			
?>
<html>
<head>
			<title>I-Tec</title>
			<link href="css/style.css" rel="stylesheet" type="text/css">
			<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
			
</head>
<body onload="HideStuff()">
		<script type="text/javascript">
			var slideimages = new Array() // create new array to preload images
			slideimages[0] = new Image() // create new instance of image object
			slideimages[0].src = "./images/banner1.jpg" // set image object src property to an image's src, preloading that image in the process
			slideimages[1] = new Image()
			slideimages[1].src = "./images/banner2.jpg"
			slideimages[2] = new Image()
			slideimages[2].src = "./images/banner3.jpg"
			slideimages[3] = new Image()
			slideimages[3].src = "./images/banner4.jpg"
			slideimages[4] = new Image()
			slideimages[4].src = "./images/banner5.jpg"
			slideimages[5] = new Image()
			slideimages[5].src = "./images/banner6.jpg"
		</script>
		<script type="text/javascript">
			function HideStuff()
			{
				document.getElementById('alert1').style.visibility="visible";
				document.getElementById('alert2').style.visibility="visible";
			}
			function CheckValidity()
			{
				var flag=0;
				if((document.forms[0].username.value.trim()=="")&&(document.forms[0].password.value==""))
				{
					document.getElementById('alert1').style.visibility="visible";
					document.getElementById('alert1').innerHTML=" Enter your username.";
					document.getElementById('alert2').style.visibility="visible";
					document.getElementById('alert2').innerHTML=" Enter your password.";
					document.forms[0].username.focus();
					flag=1;
				}
				else
				{				
						if(document.forms[0].username.value.trim()=="")
						{
							document.getElementById('alert1').style.visibility="visible";
							document.getElementById('alert1').innerHTML=" Enter your username.";
							document.forms[0].username.focus();
							document.forms[0].password.value="";
							flag=1;
						}
						else
						{
							document.getElementById('alert1').innerHTML="";
																
						}
						if(document.forms[0].password.value=="")
						{
							document.getElementById('alert2').style.visibility="visible";
							document.getElementById('alert2').innerHTML=" Enter your password.";
							document.forms[0].password.focus();
							flag=1;
						}
						else
						{
							document.getElementById('alert2').innerHTML="";
									
						}
				}
				if(flag==0)
					return true;
				else
					return false;
			}
		</script>
		<div class="header_bg">
						<div class="wrap"> 
							<div class="margin_top">
									<div class="top_menu_region">
										<div class="top_menu_style">
									     	<div class="menu">
								     	<nav>
								     		<ul>
										    	<li><a href="index.html">Home</a></li>
												<li class="active"><a href="Login.php">Login</a></li>
										     	<li><a href="ContactUs.html">Contact Us</a></li>
							     			</ul>
							     			</nav>
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
							<img id="slide"src="images/banner1.jpg" alt="" width="1190px" height="350px">
								<script type="text/javascript">
									//variable that will increment through the images
									var step=0;									
									function slideit()
									{
										 //if browser does not support the image object, exit.
										if (!document.images)
									  			return
									 	document.getElementById('slide').src = slideimages[step].src
									 	if (step<5)
									 		 step++
									 	else
									  		step=0
									 	//call function "slideit()" every 2.5 seconds
									 	setTimeout("slideit()",2000)
									}
									slideit();
								</script>
						<div class="main_body_text">
								<div  class="col_1">
											<div class="title-img1">
												<div class="title"><img src="images/login.png" alt=""/></div>
													<div class="title-desc"><h6>Member Login</h6></div>
													<div class="clear"></div> 
											</div>					
											<div class="form">
													<FORM METHOD="POST" ACTION="Login.php" onsubmit="return CheckValidity();">
															<input type=hidden name=action value="login">
															<TABLE id="table2" align="center">
																<TR>
																<td></td>
																	<TD>
																		<B>Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</B>
																		
																	</TD>
																	<TD>
																		<INPUT TYPE="TEXT" NAME="username" pattern="[a-zA-Z_0-9]+" title="Only Alphanumeric characters and underscore character">																		
																	</TD>
																	<TD>
																		<label id="alert1">
																			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																			<?php 
																				$reasons = array("password" => "Invalid Username or Password"); 
																				if ($_GET["loginFailed"]) 
																				echo $reasons[$_GET["reason"]]; 
																			?>
																		</label>										
																	
																	</TD>
																</TR>
																<tr></tr>
																<tr></tr>
																<TR>
																	<td></td>
																	<TD>
																	<br><br>
																	<B>Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</B>
																	</TD>
																	<TD>
																	<br>
																	<INPUT TYPE="PASSWORD" NAME="password">
																	</TD>
																	<TD>
																	<br><br>
																		<label id="alert2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
																	</TD>
																</TR>
																<tr>
																	<td colspan="2"></td>																
																	<td><br><INPUT class="btn btn-primary1" TYPE="submit" NAME="submit" VALUE="LOGIN" onclick="javascript:CheckValidity();"></td>												
																</tr>
															</TABLE>
															
													</FORM>													
											</div>
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
