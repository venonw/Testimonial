{if $smarty.get.p eq 'submit'}
	<div class="page-header">
		<div class="styled_title"><h1>ارسال نظر<small> نظرات و دیدگاه های خود را در رابطه با خدمات و محصولات ما ارسال کنید</small><a class="btn btn-small btn-default" href="index.php?m=v_testimonial" style="float: left;">بازگشت ›</a></h1></div>
	</div>
	{if $error.tot}
		<div class="alert-message success alert alert-block alert-success">
			<p><strong>نظر شما ثبت شد:</strong> نظر شما ثبت و پس از بررسی بر روی سایت قرار می گیرد.</p>
		</div>
	{/if}
	{if $enable}
		{if $clientonly}
			{if $loggedin}
				{include file="modules/addons/v_testimonial/form.tpl"}
			{else}
			<div class="alert-message error alert alert-block alert-error">
				<p><strong>توجه نماييد:</strong> در حال حاضر ارسال نظر تنها برای مشتریان امکان پذیر می باشد، لطفا وارد شوید.</p>
			</div>
			{/if}
		{else}
			{include file="modules/addons/v_testimonial/form.tpl"}
		{/if}
	{else}
		<div class="alert-message error alert alert-block alert-error">
			<p><strong>توجه نماييد:</strong> در حال حاضر ارسال نظرات غیرفعال می باشد.</p>
		</div>
	{/if}
{else}
	
	<div class="page-header">
		<div class="styled_title"><h1>نظرات مشتریان ما<small> مشتریان ما در رابطه با خدمات ما چه می گویند؟</small><a class="btn btn-small btn-default" href="index.php?m=v_testimonial&p=submit" style="float: left;"><i class="glyphicon glyphicon-send"></i> ارسال نظر</a></h1></div>
	</div>

	{foreach item=row from=$rows }
	<div class="well well-small">
		<blockquote>
		<div class="well well-small">
		  <p>{$row.message}</p>
		</div>
		  <small><i class="glyphicon glyphicon-user"></i> <cite title="Source Title"><span class="label label-info">{$row.name}</span></strong></cite></small>
		</blockquote>
	</div>
	{/foreach}
	<div class="btn-group">
		{if $pre}<a class="btn btn-info" href="index.php?m=v_testimonial&page={$prepage}"><i class="glyphicon glyphicon-chevron-right"></i> صفحه قبلی</a>{/if}
		<a class="btn btn-info" href="index.php?m=v_testimonial"><i class="glyphicon glyphicon-home
"></i> صفحه اول</a>
		{if $next}<a class="btn btn-info" href="index.php?m=v_testimonial&page={$nextpage}">صفحه بعد <i class="glyphicon glyphicon-chevron-left"></i> </a>{/if}
	</div>
{/if}
	