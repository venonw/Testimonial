{if $error.fields eq 1}
	<div class="alert-message info alert alert-block alert-info">
		<p>توجه نمایید وارد کردن نام و متن دیدگاه الزامی می باشد.</p>
	</div>
{/if}

	<form method="POST" action="index.php?m=v_testimonial&p=submit" enctype="multipart/form-data">
	<input type="hidden" name="uid" value="{$clientsdetails.id}" />
	<fieldset>
	<div class="clearfix">
		<div class="clearfix">
			<label for="name">نام و نام خانوادگی</label>
			<div class="row">
				<div class="input col-md-6">
					<input class="form-control" type="text" name="name" id="name" value="{$smarty.post.name}" />
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<label for="message">دیدگاه شما (لطفا دیدگاه خود را به طور مختصر وارد نمایید)</label>
		<div class="row">
			<div class="input col-md-6">
				<textarea class="form-control" name="message" id="message" rows="6" style="min-width:630px;">{$smarty.post.message}</textarea>
			</div>
		</div>
	</div>

	<input type="hidden" name="reluserid" value="{$clientsdetails.id}" />
	<input type="hidden" name="status" value="sent" />
	</fieldset>
	<hr />
	<div class="form-actions">
	  <button type="submit" class="btn btn-primary">ثبت</button>
	  <button type="button" class="btn">لغو</button>
	</div>
</form>
