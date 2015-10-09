<div id='case-studies' class='sidebox'>
	<div class="sidebox-top" id='case-studies-top'>case studies</div>

	<div id='case-studies-body' class='sidebox-body'>
		
	{section name=case_studies loop=$case_studies}
		<div class='case-studies-item sidebox-item'>	
			<div class='case-studies-thumb'><img src='/cmsimages/emptythumb.gif' alt='case study thumb' /></div>
			<span class='sidebox-item-text'>{$case_studies[case_studies].title}</span>
			<span class='case-studies-text'>{$case_studies[case_studies].description}</span>
			<a href='{$case_studies[case_studies].link}' class="sidebox-link">more</a>
			<br clear='all'/>
		</div>
		{sectionelse}
		<div class='case-studies-item sidebox-item'>	
			<span class='case-studies-text'>{$no_case_studies}</span>
		</div>
	{/section}
		
	</div>
	<div class="sidebox-baselink" id='case-studies-base'>
	<a class="case-studies-base-link sidebox-base-link" href="/case_studies">view all case studies</a>
	</div>
</div>
