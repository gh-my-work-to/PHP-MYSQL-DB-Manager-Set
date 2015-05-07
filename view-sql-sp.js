$('#setHeader').click(function()
{
	var str = "select {{header}}_posts.ID, {{header}}_posts.post_content, {{header}}_posts.post_date, tmp_last.slg\n \n from {{header}}_posts right join\n\n (select tmp_taxid.tetaxid, tmp_taxid.termid, tmp_taxid.slg as slg,\n\n {{header}}_term_relationships.object_id as oid\n\n from {{header}}_term_relationships right join\n\n (select {{header}}_term_taxonomy.term_taxonomy_id as tetaxid, \n \n {{header}}_term_taxonomy.term_id as termid,\n \n {{header}}_terms.slug as slg\n \n from {{header}}_term_taxonomy\n \n right join {{header}}_terms\n \n on {{header}}_term_taxonomy.term_id = {{header}}_terms.term_id) as tmp_taxid\n\n on {{header}}_term_relationships.term_taxonomy_id = tmp_taxid.tetaxid) as tmp_last\n\n on {{header}}_posts.ID = tmp_last.oid\n\n where tmp_last.slg='{{slug}}'"
	
	.split('{{header}}').join($('#header').val())
	.split('{{slug}}').join($('#slug').val());
	
	$('#sql').val(str);
});
