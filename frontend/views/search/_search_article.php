<?php
if($articles_result != null && count($articles_result)>0) {
?>	
<div class="row">

	<ul class="article-serach-section-result-article">
		<li>
			<span>Article Name 1x1x1</span>
			<br/>
			<i>Authors: </i><span class="article-serach-section-result-users">User Name 1x1x1x1; User Name 1x1x1x2; User Name 1x1x1x3</span>
		</li>
	</ul>
	<span class="article-serach-section-result-volume"><u><i>Volume:</i></u> Volume Name 1</span>
	<br/>
	<span class="article-serach-section-result-issue"><u><i>Issue:</i></u> Issue Name 11</span>
	<br/>
	<span class="article-serach-section-result-section"><u><i>Section:</i></u> Section Name 1x1</span>
	<br/>
	
</div>
<hr style="border-top: 3px dashed #eee;" />
<div class="row">

	<ul class="article-serach-section-result-article">
		<li>
			<span>Article Name 1x2x1</span>
			<br/>
			<i>Authors: </i><span class="article-serach-section-result-users">User Name 1x2x1x1; User Name 1x2x1x2; User Name 1x2x1x3</span>
		</li>
	</ul>
	<span class="article-serach-section-result-volume"><u><i>Volume:</i></u> Volume Name 1</span>
	<br/>
	<span class="article-serach-section-result-issue"><u><i>Issue:</i></u> Issue Name 11</span>
	<br/>
	<span class="article-serach-section-result-section"><u><i>Section:</i></u> Section Name 1x2</span>
	<br/>		
	
</div>
<hr style="border-top: 3px dashed #eee;" />
<div class="row">	

	<ul class="article-serach-section-result-article">
		<li>
			<span>Article Name 2x1x1</span>
			<br/>
			<i>Authors: </i><span class="article-serach-section-result-users">User Name 2x1x1x1; User Name 2x1x1x2; User Name 2x1x1x3</span>
		</li>
	</ul>
	<span class="article-serach-section-result-volume"><u><i>Volume:</i></u> Volume Name 2</span>
	<br/>
	<span class="article-serach-section-result-issue"><u><i>Issue:</i></u> Issue Name 22</span>
	<br/>
	<span class="article-serach-section-result-section"><u><i>Section:</i></u> Section Name 2x1</span>
	<br/>

</div>
<hr style="border-top: 3px dashed #eee;" />
<div class="row">

	<ul class="article-serach-section-result-article">
		<li>
			<span>Article Name 2x2x1</span>
			<br/>
			<i>Authors: </i><span class="article-serach-section-result-users">User Name 2x2x1x1; User Name 2x2x1x2; User Name 2x2x1x3</span>
		</li>
	</ul>
	<span class="article-serach-section-result-volume"><u><i>Volume:</i></u> Volume Name 2</span>
	<br/>
	<span class="article-serach-section-result-issue"><u><i>Issue:</i></u> Issue Name 22</span>
	<br/>
	<span class="article-serach-section-result-section"><u><i>Section:</i></u> Section Name 2x2</span>
	<br/>
	
</div>	
<?php
} else {
	echo "<div class='serach-section-empty-result'>No Articles are found!</div>";
}
?>