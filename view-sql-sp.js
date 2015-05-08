$('#setHeader').click(function()
{
	var str = "SELECT {{header}}_posts.post_title, {{header}}_posts.post_content, {{header}}_posts.post_date, {{header}}_terms.slug\n \n FROM {{header}}_posts \n\n RIGHT JOIN {{header}}_term_relationships ON {{header}}_term_relationships.object_id = {{header}}_posts.ID\n\n RIGHT JOIN {{header}}_term_taxonomy ON {{header}}_term_taxonomy.term_taxonomy_id = {{header}}_term_relationships.term_taxonomy_id\n\n RIGHT JOIN {{header}}_terms ON {{header}}_terms.term_id = {{header}}_term_taxonomy.term_id\n\n WHERE {{header}}_terms.slug = '{{slug}}'\n \n ORDER BY {{header}}_posts.post_date DESC\n \n LIMIT 0, 1"
	
	.split('{{header}}').join($('#header').val())
	.split('{{slug}}').join($('#slug').val());
	
	$('#sql').val(str);
});
