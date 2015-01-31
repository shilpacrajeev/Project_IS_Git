<!DOCTYPE HTML>
<?php 
	session_start();
    if(!$_SESSION["id"]) 
	  	header("Location: Login.php");
?>
<html>
<head>
			<title>I-Tec</title>
			<link href="css/style.css" rel="stylesheet" type="text/css">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
</head>
<body>
			<div class="header_bg"><label class="user">Hi, <?php echo $_SESSION["username"];?></label>
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
										</nav><div class="clear"> </div>
									</div>	
									<div class="col_2_of_2 span_2_of_2">
												<div class="title-img1">
													<div class="title"><img src="images/book.png" alt=""/></div>
														<div class="title-desc"><h6>Courses</h6></div>
														<div class="clear"></div> 
												</div>					
												<p class="desc-middle"><strong> Welcome Teacher</strong></p>
												<p class="desc1"> This is an Integrated System to consolidate data from multiple databases</p>
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
