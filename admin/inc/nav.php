		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">

			<!-- User info -->
			<div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as is --> 
					
					<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
						<?php 
						if ($_SESSION['user_PicPath'] == null || $_SESSION['user_PicPath'] == '')
						{
							$empProfilePic = 'no-image.png';
						}
						else 
						{
							$empProfilePic = substr(strrchr($_SESSION['user_PicPath'], "/"), 1); 
						}
						?>
						<img src="<?php echo UPLOADS_URL; ?>/employee_images/thumbs/<?php echo $empProfilePic; ?>" alt="me" class="online" /> 
						<span>
						<?php 
						if ($_SESSION['user_Type'] == 'A')
						{
							$user_type = "Admin";
						}
						elseif ($_SESSION['user_Type'] == 'M')
						{
							$user_type = "Manager";
						}
						elseif ($_SESSION['user_Type'] == 'E')
						{
							$user_type = "Employee";
						}
						elseif ($_SESSION['user_Type'] == 'C')
						{
							$user_type = "Clerk";
						}
						elseif ($_SESSION['user_Type'] == 'T')
						{
							$user_type = "Top Management";
						}
						
						echo $_SESSION['user_FirstName']." (".$user_type.")";
						?>
						</span>
						<i class="fa fa-angle-down"></i>
					</a> 
					
				</span>
			</div>
			<!-- end user info -->

			<!-- NAVIGATION : This navigation is also responsive

			To make this navigation dynamic please make sure to link the node
			(the reference to the nav > ul) after page load. Or the navigation
			will not initialize.
			-->
			<nav>
				<!-- NOTE: Notice the gaps after each icon usage <i></i>..
				Please note that these links work a bit different than
				traditional hre="" links. See documentation for details.
				-->
				<?php
					$ui = new SmartUI();
					$ui->create_nav($page_nav)->print_html();
				?>

			</nav>
			<span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>

		</aside>
		<!-- END NAVIGATION -->