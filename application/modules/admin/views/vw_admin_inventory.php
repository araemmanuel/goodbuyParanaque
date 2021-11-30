<form action = "<?php echo base_url('admin/product/add');?>" method="POST">
Category:
<select name = "cat_name">
</select><br><br>

Subcategory:
<select name = "subcat_name">
</select><br><br>

Product Name:
<input type = "text" name = "prod_name"/><br><br>
Attributes:
<input type = "text" name = "prod_attrib"/><br><br>
Description
<input type = "text" name = "prod_desc"/><br><br>

Brand
<input type = "text" name = "prod_desc"/><br><br>

Quantity
<input type = "text" name = "prod_qty"/><br><br>
Price
<input type = "text" name = "prod_price"/><br><br>
Primary Image:
<input type = "file" name = "prod_primary_img"/><br><br>

Thumbnail Images:
<input type = "file" name = "prod_imgs" multiple /><br><br>
<input type = "submit" name = "prod_add" value = "ADD PRODUCT"/><br><br>
</form>

