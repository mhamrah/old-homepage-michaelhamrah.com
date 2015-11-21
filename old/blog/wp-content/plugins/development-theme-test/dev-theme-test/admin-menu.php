<style>
	.kw_td_hover:hover {
		cursor: pointer;
		background-color: #DAE6F4;
	}
</style>

<script>
function kw_td_rowclick(subdomain, theme, dev, pwd) {
	document.getElementById("kw_td_edit_sd").value = subdomain;
	document.getElementById("kw_td_edit_id").value = subdomain;
	document.getElementById("kw_td_delete").value = subdomain;

	document.getElementById("kw_td_commit").value = theme;
	//document.getElementById("kw_td_revert").value = theme;


	document.getElementById("kw_td_edit_dt").checked = (dev == 1 ? true : false);
	document.getElementById("kw_td_edit_ao").checked = (pwd == 1 ? true : false);

	for(var i = 0; i < document.getElementById("kw_td_edit_th").length; i++) {
		if(document.getElementById("kw_td_edit_th")[i].value == theme)
			document.getElementById("kw_td_edit_th").selectedIndex = i;
	}

	document.getElementById("kw_td_new").style.display = 'none';
	document.getElementById("kw_td_edit").style.display = 'block';
	document.getElementById("kw_td_dev_changes").style.display = (dev == 1 ? 'block' : 'none');
}

function kw_td_cancel() {
	document.getElementById("kw_td_new").style.display = 'block';
	document.getElementById("kw_td_edit").style.display = 'none';
	document.getElementById("kw_td_dev_changes").style.display = 'none';
}

function kw_td_addcheck() {
	var value = document.getElementById('kw_td_newdomain').value;
	var subdomains = {
		<?php
		$first = true;
		foreach ($data as $key=>$val) {
			if ($first == true) {
				$first = false;
			} else {
				echo ", ";
			}
			echo "'" . $key . "':''";
		}
		?>
	};

	if (value == "") {
		alert("Invalid subdomain");
		return false;
	} else if (value in subdomains) {
		alert("Duplicate subdomain");
		return false;
	}


	return true;
}

function kw_td_del() {
	if (confirm("Are you sure you want to delete this?")) {
		document.kw_td_delete_form.submit();
	}
}
</script>

<h1>Development Theme Test by <em>Kwista</em></h1>

<?php if (!empty($errors)) { ?>
<div class="error">
	<?php echo join('<br/>', $errors); ?>
</div>
<p></p>
<?php } ?>

<p>Trying to edit your theme or test a new theme can be very difficult without a testing server or a dev location. Dev Theme Test allows you to create a password protected subdomain such as <strong>dev</strong>.yoursite.com in order to make modifications to your current theme or a new theme while still displaying your original theme to site visitors. Need help? <a href="http://www.kwista.com/dev-theme-test/" target="_blank">[Read The Documentation]</a></p>

<div style="width: 70%; float:left;">
<table class="widefat post fixed" cellspacing="0" style="width: 100%">
	<thead>
		<tr>
			<th scope="col" id="cb" class="manage-column" style="width:50px">&nbsp;</th>
			<th scope="col" id="title" class="manage-column">Subdomain</th>
			<th scope="col" id="author" class="manage-column" style="width:150px">Theme</th>
			<th scope="col" id="author" class="manage-column" style="width:55px">Dev</th>
			<th scope="col" id="author" class="manage-column" style="width:55px">Pwd</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope="col" id="cb" class="manage-column column-cb check-column" style="">&nbsp;</th>
			<th scope="col" id="title" class="manage-column column-title" style="">Subdomain</th>
			<th scope="col" id="author" class="manage-column column-author" style="">Theme</th>
			<th scope="col" id="author" class="manage-column" style="width:15px">Dev</th>
			<th scope="col" id="author" class="manage-column" style="width:35px">Pwd</th>
		</tr>
	</tfoot>

	<tbody>
		<?php $count = 0;?>
		<?php foreach($data as $subdomain=>$theme) { ?>
			<?php $count++; ?>
				<tr id="kw_tdrow_<?php echo $count;?>" class="kw_td_hover" onclick="kw_td_rowclick('<?php echo $subdomain;?>','<?php echo $theme['t'];?>','<?php echo $theme['d'];?>','<?php echo $theme['p'];?>')">
					<td><?php echo $count;?>.</td>
					<td><?php echo $subdomain;?></td>
					<td><?php echo $theme['t'];?></td>
					<td><?php if ($theme['d']) echo 'X';?>&nbsp;</td>
					<td><?php if ($theme['p']) echo 'X';?>&nbsp;</td>
				</tr>
		<?php } ?>
	</tbody>
</table>

<p></p>

<form method="POST" onsubmit="return kw_td_addcheck()">
<input type="hidden" name="addnew" value="1" />
<table id="kw_td_new" class="widefat post fixed" cellspacing="0" style="width: 450px">
	<thead>
		<tr>
			<th scope="col" id="cb" class="manage-column" colspan="2">Add New</th>
		</tr>
	</thead>


	<tbody>
		<tr>
			<td style="width:200px;"><strong>Subdomain</strong>: <a href="http://www.kwista.com/dev-theme-test/creating-subdomains/" target="_blank">?</a></td>
			<td style="width:250px;"><input id="kw_td_newdomain" type="text" name="subdomain" style="width: 200px" /></td>
		</tr>
		<tr>
			<td><strong>Theme</strong>:</td>
			<td>
			<select name="theme" style="width: 200px">
				<?php foreach ($themes as $theme) { ?>
					<option><?php echo $theme;?></option>
				<?php } ?>
			</select>
			</td>
		</tr>
		<tr>
			<td><strong>Is Development? <a href="http://www.kwista.com/dev-theme-test/is-development/" target="_blank">?</a></strong><br />
<span style="font-size:10px;">Creates duplicate copy of the theme renamed with "_dev"</span></td>
			<td><input type="checkbox" name="dev" /></td>
		</tr>
		<tr>
			<td><strong>Users Only: <a href="http://www.kwista.com/dev-theme-test/users-only/" target="_blank">?</a></strong><br />
<span style="font-size:10px;">Only allow logged-in users to view the subdomain.</span></td>
			<td><input type="checkbox" name="pwd" /></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:right;"><input type="submit" value="Save"></td>
		</tr>
	</tbody>
</table>
</form>

<form method="POST">
<input id="kw_td_edit_id" type="hidden" name="edit" value="" />
<table id="kw_td_edit" class="widefat post fixed" cellspacing="0" style="width: 450px; display:none;">
	<thead>
		<tr>
			<th scope="col" id="cb" class="manage-column" colspan="2">Edit</th>
		</tr>
	</thead>


	<tbody>
		<tr>
			<td style="width:200px;">Subdomain:</td>
			<td style="width:250px;"><input id="kw_td_edit_sd" type="text" name="subdomain" style="width: 200px" /></td>
		</tr>
		<tr>
			<td>Theme:</td>
			<td>
			<select id="kw_td_edit_th" name="theme" style="width: 200px">
				<?php foreach ($themes as $theme) { ?>
					<option><?php echo $theme;?></option>
				<?php } ?>
			</select>
			</td>
		</tr>
		<tr>
			<td>Is Development?</td>
			<td><input id="kw_td_edit_dt" type="checkbox" name="dev" value="1" /></td>
		</tr>
		<tr>
			<td>Users Only:</td>
			<td><input id="kw_td_edit_ao" type="checkbox" name="pwd" value="1" /></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:right;"><input type="button" value="Cancel" onclick="kw_td_cancel()">&nbsp;&nbsp;&nbsp;<input type="button" value="Delete" onclick="kw_td_del()">&nbsp;&nbsp;&nbsp;<input type="submit" value="Save"></td>
		</tr>
	</tbody>
</table>
</form>

<p></p>

<table id="kw_td_dev_changes" class="widefat post fixed" cellspacing="0" style="width: 450px; display:none;">
	<thead>
		<tr>
			<th scope="col" id="cb" class="manage-column" colspan="2" style="width: 450px">Dev Controls</th>
		</tr>
	</thead>


	<tbody>
		<tr>
			<td style="text-align: center;">
				<form method="POST">
					<input id="kw_td_commit" type="hidden" name="commit" value="">
					<input type="submit" value="Commit Changes" />
				</form>
			</td>
			<td style="text-align: center;">
				<!--<form method="POST">
					<input id="kw_td_revert" type="hidden" name="revert" value="">
					<input type="submit" value="Revert Changes" />
				</form>-->
			</td>
		</tr>
	</tbody>
</table>

<form name="kw_td_delete_form" method="POST">
<input id="kw_td_delete" type="hidden" name="delete" value="" />
</form>

<p><a href="http://www.kwista.com/other/donate" target="_blank">Donate Please</a></p>

</div>

<div style="width:155px; text-align: center; float:left">
<a href="http://www.kqzyfj.com/click-3121140-10459962" target="_blank">
<img src="http://www.ftjcfx.com/image-3121140-10459962" width="125" height="125" alt="DreamTemplate - Web Templates" border="0"/>
Professional Wordpress Templates
</a><br><br>

<a href="http://www.thirstywebsites.com/" target="_blank">
<img src="http://www.kwista.com/images/thirstyweb-promo.png" width="125" height="125" alt="Thirsty Websites - Custom Professional Web Development" border="0"/>
Custom Professional Web Design
</a><br><br>

<a href="http://www.shoutdomains.com/" target="_blank">
<img src="http://www.kwista.com/images/shoutdomains-promo.png" width="125" height="125" alt="Shout Domains - Free Domaining Tools" border="0"/>
Free Domaining Tools
</a>


</div>