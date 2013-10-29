<?php

  class Mag extends CI_Controller {
  		
  	var $standard_select = 'articles.aid, articles.title_url,
  							articles.publish_status AS publish_status,
					        CASE publish_status
					           WHEN "0" THEN "rejected - marked for deletion"
					           WHEN "1" THEN "draft"
					           ELSE "live"
					        END AS publish_status_out,
  							articles.title, articles.intro_txt, articles.primary_category, articles.headliner, articles.timestamp_publish, articles.author_1, articles.mailout, articles.views, articles_fp.aid AS featured,
  							categories.shortname AS categoryShortname, categories.name AS categoryName,
  							GROUP_CONCAT(categoriesRelated.name SEPARATOR \', \') AS related_categories';

  	var $img_upload_window_atts = array(
              'width'      => '660',
              'height'     => '360',
              'scrollbars' => 'no',
              'status'     => 'no',
              'resizable'  => 'no',
              'screenx'    => '100',
              'screeny'    => '100'
    );
       
	  var $views = array (
	  	'contributors' => 'admin/mag/contributors'
	  );
  
	  var $festival_status = array (
	  	'false' => 'None',
	  	'guide' => 'Festival guide/review',
	  	'event' => 'Festival event',
	  );

	 function __construct() {
	     parent::__construct();
	 	 $this->auth->restrict_backend ();
	 	 
	 	 $this->load->library('adminlayout');
	     
	     $this->load->model('magazineminimodel');
	     $this->load->model('magazinemodel', 'mag');
		 $this->load->model('franchisemodel');
	     $this->load->library('a_media_upload');

	     $this->load->helper('text');	     
	 }
  
  function index () {
  	$this->listing ();	
  }
     
  function old () {
  	$result=$this->db->query("SELECT * FROM db12781_magazinenew.articles ORDER BY timestamp_publish DESC");
  	foreach ($result->result() AS $row) {
  		echo "<a href=\"/admin/mag/oldarticle/".$row->aid."\">".$row->title."</a><br />\n";
  	}
  }
  
  function _oldArticleAuthor ($aid) {
  	$query=$this->db->query("SELECT * FROM db12781_magazinenew.authors_works WHERE itemid=\"".$aid."\"");
  	$result=$query->row_array();
  	
  	if ($result['uid']=='0') {
  		return $result['author_name'];
  	} else {
  		$query=$this->db->query("SELECT * FROM ci_users.users WHERE uid=\"".$result['uid']."\"");
  		$result=$query->row_array();
  		return $result['firstname']." ".$result['surname'];
  	}
  }
  
  function oldarticle ($aid) {
  	$query=$this->db->query("SELECT * FROM db12781_magazinenew.articles WHERE aid=\"".$aid."\"");
  	$result=$query->row_array();
  	
  	$main_img=$this->db->query("SELECT content FROM db12781_magazinenew.creatives WHERE creativeid = (SELECT creativeid FROM db12781_magazinenew.articles_creatives WHERE aid=\"".$aid."\" LIMIT 1)");
  	$img=$main_img->row_array();
  
  	?>
  	<html>
  	<head>
  	</head>
  	
  	<body>
  	<div style="width: 700px">
  	<?php  	
  	echo "<h1>".$result['title']."</h1>";
  	echo "by <b>".$this->_oldArticleAuthor ($result['aid'])."</b><br />";
  	echo "Published <b>".date ("d - M - Y", $result['timestamp_publish'])."</b><br />";
  	
  	echo "<img src=\"http://www.dontpaniconline.com/var_/uploads/magazine/".$img['content']."\" /><br />";
  	
  	$content=str_replace('/includes/phpthumb/phpThumb.php?src=/var', 'http://www.dontpaniconline.com/var_', $result['content']);

  	$content=str_replace('/var/', '/var_/', $content);

  	$content=str_replace('&amp;aoe=1&amp;w=524', '', $content);
  	$content=str_replace('&amp;aoe=1&amp;w=404', '', $content);
  	$content=str_replace('&amp;aoe=1&amp;w=500', '', $content);
  	
  	echo $content;
  	

   	?>
  	</div>
  	</body>
  	</html>
  	<?php
  	//showvars ($result);
  }
    
  /* AJAX request function to search contributors */
  function filtrate_contributors ($type=false) {
  	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
	  	$frag=$this->input->post('name');
	  	$active_div=$this->input->post('active_div');
	  	$input_id=$this->input->post('input_id');
	  	
	  	if ($frag && strlen($frag)>1) {
		  	$result=$this->mag->SearchContributors ($frag);
		  	
		  	if ($type) {
		  		if ($type=='1') {
				  	if ($result) {
				  		foreach ($result->result() AS $row) {
				  			echo "<a href=\"javascript: void(0);\" onclick=\"javascript: $('#".$input_id."').val('".$row->name."'); $('#".$active_div."').hide();\">".$row->name."</a><br />\n";
				  		}
				  	}
		  		} else {
		  			echo $this->_contributors_output ($result);
		  		}
		  	}
	  	}
  	}
  }
  
  /* AJAX request function to search articles */
  function filtrate_articles ($type=false) {
  		$frag=$this->input->post('key');
 
  		if ($frag && strlen($frag)>1 && $type) {

  			$hidden = ($type=='1') ? false : true;

  					switch ($type) {
  						case '1':							
  							$result=$this->mag->filtrate_video_and_mag ($frag, $current_id=false);
  							if ($result) {
	  							$current_aid=($this->input->post('current')*1);
								foreach ($result AS $row) {
									if (($row['aid']*1)!==$current_aid) {
										echo "<a href=\"javascript: void(0);\" onclick=\"javascript: article_relations ('magazine', 'relate', '".$current_aid."', '".$row['aid']."', '".$row['section']."');\">[relate ".$row['section']."]... </a>".$row['title']."<br />\n";
									}
								}
  							}

						break;

  						case '2':
  							$result=$this->mag->performSearchByKeyword($frag, $hidden, 30, 0, true);
  							if ($result) {
	  							foreach ($result AS $row) {
									echo "<a href=\"/admin/mag/edit/".$row['aid']."\">".$row['title']."</a><br />\n";
								}
  							}
  						break;
  				}
  		}
  }
  
  function relations ($operation) {
  	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
  		$current = $this->input->post('current');
  		$relate	 = $this->input->post('relate');
  		$section = $this->input->post('related_section');

  		if ($current && $relate && $section) {
  			($operation=='relate')
  				? $this->mag->DynamicArticleRelations ('relate', $current, $relate, $section)
  				: $this->mag->DynamicArticleRelations ('unrelate', $current, $relate, $section);
			
				$this->cache->delete('magazine_articles/mag_article_'.md5($current));
				
  			echo 'true';
  		} else {
  			echo 'One of the parameters is not caught!';
  		}
  	}
  }
    
  function contributors ($contID=false) {
  	
  	if ($contID && is_numeric($contID)) {
  		$data['action']='1';
		$data['contributor']=$this->mag->GetContributor ($contID);
		$pageTitle=($data['contributor']) ? 'Magazine contributors: '.$data['contributor']['name'] : 'Magazine contributors';
	 } else {
  		$data['action']='2';
	  	$full_listing=$this->mag->SearchContributors ();
	  	
	  	if ($full_listing) {
	  		$pageTitle='Magazine contributors: '.$full_listing->num_rows();
		
			$start=$this->_GeneratePagination ('/admin/mag/contributors/?', $full_listing->num_rows(), 100);
	
			$results=$this->mag->SearchContributors (false, 100, $start);
			$data['contributors']=$this->_contributors_output ($results);
		
	  	} else {
	  		$data['contributors']=false;
	  		$pageTitle='Magazine contributors: 0';
	  	}

  	}
  	
  	$this->load_view($this->views['contributors'], $data, $pageTitle);
  	
  }
  
  function _contributors_output ($stream) {
 		if ($stream) {
	  		$i=1;
	 		$output='';
			foreach ($stream->result() AS $row) {
				$output .= "<div class=\"grid_3 align-center\">
								<a href=\"/admin/mag/contributors/".$row->id."\"><img src=\"/i/icons/user-icon.png\" width=\"50\" alt=\"".$row->name."\" title=\"".$row->name."\" /></a>
								<br />
								<a href=\"/admin/mag/contributors/".$row->id."\">".$row->name."</a>
							</div>\n";
				if ($i=='4') { $output .= "<div class=\"clear append-bottom\"></div>\n"; $i=0; }
				$i++;
			}
			
			return $output;
 		} else {
 			return false;
 		}
  }
  
  function process_contributor ($id=false) {
  	if ($id && is_numeric($id)) {
  		if (isset($_POST['submit_contributor'])) {
  			$this->mag->UpdateContributor ($id, $_POST);
  			$this->contributors($id);
  		}
  	} else {
  		redirect ('admin/mag/contributors');
  	}
  }
  
  function carousel_crop ($aid) {
  	
  	$data['article_info']=$this->mag->fetchArticles_forCMS ('articles.aid, articles.headliner', array ('articles.aid' => ($aid*1)), false, 1, 0);
		
	if ($data['article_info']->num_rows()==1) {
				
		$data['article_info'] = $data['article_info']->row_array();  	
  		
	  	$data['form_link']='/admin/mag/carousel_crop/'.$aid;
	  	$data['source_img']='/media/magazine/604x/'.$data['article_info']['headliner'];
	  	$data['filename']=$data['article_info']['headliner'];
	  	
	  	if ($this->input->post('submit_crop', TRUE)) {
	  		
	  		if (is_numeric($this->input->post('x1')) &&
				is_numeric($this->input->post('w')) &&
				is_numeric($this->input->post('y1')) &&
				is_numeric($this->input->post('h'))) {
								  	  	
								  	  	$thumb_in_action=$_SERVER['DOCUMENT_ROOT']."/media/magazine/604x400/".$this->input->post('current_img');
								  	  	if (strlen($this->input->post('current_img'))>0 && file_exists($thumb_in_action)) {
								  	  		unlink ($thumb_in_action);
								  	  	}
								  	  	
								  	  	$scale = (604/$this->input->post('w'));
					
								  	  	$cropped = $this->a_media_upload->resizeThumbnailImage($thumb_in_action, $_SERVER['DOCUMENT_ROOT']."/media/magazine/604x/".$this->input->post('current_img'),
								  	  														   $this->input->post('w'),  $this->input->post('h'),
								  	  														   $this->input->post('x1'), $this->input->post('y1'), $scale);
				$this->cache->delete_group('fpFeatured_');  	
				$this->auth->extend_users_session (array('cms_post_action_message' => 'Your image has been cropped and saved! <a href="javascript: self.close ();">Click here to close this window</a>'));

			}
	  	}
	  	
	  	$this->load->view ('admin/common/carousel_crop', $data);
  	}
  }
   
  function imgupload () {
  	$fileUpload=$this->a_media_upload->catch_file ('media_filename', $_SERVER['DOCUMENT_ROOT']."/media/magazine/original_files/");
	
  	if (($fileUpload['attempt'] && $fileUpload['result']) || isset($_POST['media_file_www']) && strlen($_POST['media_file_www'])>3) {

  			  $success=false;
		
  			  if (($fileUpload['attempt'] && $fileUpload['result'])) {
				  $this->place_picture_files ($fileUpload['filename']);
				  $data_to_db['headliner']=$fileUpload['filename'];
				  $success=true;
  		      } elseif (isset($_POST['media_file_www'])) {
  		      	  $copied_image = $this->a_media_upload->copy_img_from_www ($_POST['media_file_www'], $_SERVER['DOCUMENT_ROOT'].'/media/magazine/original_files/');
  		      	  
  		      	  if ($copied_image) {
	  		      	  $this->place_picture_files ($copied_image);
					  $data_to_db['headliner']=$copied_image;					  
					  $success=true;
  		      	  }
  		      }
	
			if ($success) { ?>
					<input type="button" value="Finish" onclick="javascript: sendValue ('<?php echo $data_to_db['headliner']; ?>');" />
					<script language="JavaScript">
						function sendValue (filename) {

							window.opener.$("#imagery").show();
							
							window.opener.document.getElementById('thumbnail').src = "/media/magazine/604x/" + filename;
							window.opener.document.getElementById('current_thumbnail').src = "/media/magazine/220x132/" + filename;
							window.opener.document.getElementById('t_preview').src = "/media/magazine/604x/" + filename;

							window.opener.document.getElementById('uploaded_media').value=filename;
							
							window.close();
						}
					</script>
	<?php
			}
	  	} else {
	  		$data['submit_url']=base_url().'admin/mag/imgupload';
	  		$this->load->view ('admin/common/img_upload', $data);
	  	}
  }

  private function place_picture_files ($filename) {
  		$entryFilePaths = array (
			'full_path'		=> $_SERVER['DOCUMENT_ROOT']."/media/magazine/original_files/".$filename,
			'604x_dest'		=> $_SERVER['DOCUMENT_ROOT']."/media/magazine/604x/".$filename,
  			'220x132_dest'  => $_SERVER['DOCUMENT_ROOT']."/media/magazine/220x132/".$filename
		);	

		$this->a_media_upload->copy_uploaded_file ($entryFilePaths['full_path'], $entryFilePaths['604x_dest'], 604);
		$this->a_media_upload->crop_snapshot ($entryFilePaths['full_path'], $entryFilePaths['220x132_dest'], 220, 132);
 	}

	private function _DeleteArticlesImages ($a_info=false) {
	  if ($a_info && !empty($a_info['headliner'])) {
		$path[0]=$_SERVER['DOCUMENT_ROOT']."/media/magazine/original_files/".$a_info['headliner'];
		$path[1]=$_SERVER['DOCUMENT_ROOT']."/media/magazine/220x132/".$a_info['headliner'];
		$path[2]=$_SERVER['DOCUMENT_ROOT']."/media/magazine/604x/".$a_info['headliner'];
		$path[3]=$_SERVER['DOCUMENT_ROOT']."/media/magazine/604x400/".$a_info['headliner'];
			for ($i=0; $i!==count($path); $i++) {
				if (file_exists($path[$i])) { unlink ($path[$i]); }
			}
		}
	}
         
  function _GeneratePagination ($start_link, $total, $per_page=false) {
			$this->load->library('pagination');
			$config['base_url'] 			= $start_link;
			$config['total_rows'] 			= $total;
			$config['per_page']   			= ($per_page) ? $per_page : 15;
			$config['page_query_string'] 	= FALSE;
			$config['uri_segment'] 			= get_page_segment_number();
			$config['cur_tag_open'] 		= '&nbsp;<a class="ui-state-active" href="javascript: void(0);">';
			$config['cur_tag_close'] 		= '</a>';
			$config['num_links'] 			= 4;
			$this->pagination->initialize($config);
			
			return $this->uri->segment(get_page_segment_number());
	}
  
	function _GenerateSessionParams () {

		if ($this->uri->segment(4)) {
			if (is_numeric($this->uri->segment(4))) {
				$params['fid']=$this->uri->segment(4);
				$this->auth->extend_users_session (array('active_mag_city' => $params['fid']), false);
			} elseif ($this->uri->segment(4)=='reset') {
				$this->auth->remove_extended_data ('active_mag_city');
				$params['fid']=false;
			} else {
				$params['fid']=($this->auth->get_extended_data ('active_mag_city')) ? $this->auth->get_extended_data ('active_mag_city') : false;
			}
		} else {
			$params['fid']=($this->auth->get_extended_data ('active_mag_city')) ? $this->auth->get_extended_data ('active_mag_city') : false;
		}

		if ($params['fid']) {
			$params['city']=$this->franchisemodel->get($params['fid'], 'name');
		} else {
			$params['city']="All cities";
		}
		
		return $params;
	}	

	function listing () {		
		$params=$this->_GenerateSessionParams ();
		
		$data['city_url']=base_url().'admin/mag/listing/';
		
		$where = (isset($params['fid']) && $params['fid'])
			  ? array('articles.fid' => $params['fid'])
			  : array();
		
		$full_listing=$this->mag->fetchArticles_forCMS ($this->standard_select, $where, 'articles.aid DESC');
				
		if ($full_listing) {
			$total=$full_listing->num_rows();

			$start = $this->_GeneratePagination ('/admin/mag/listing/page/', $total);

			$data['listing']=$this->mag->fetchArticles_forCMS ($this->standard_select, $where, 'articles.aid DESC', 15, $start);
		
			$data['pagination']=$this->pagination->create_links();
		} else {
			$total=0;
			$data['listing']=false;
		}
		
		$data['extra_links']="<a href=\"/admin/mag/drafts/\" title=\"Show only draft articles\">Show only draft articles (".$params['city'].")</a> | <a href=\"/admin/mag/rejected/\" title=\"Show only rejected articles\">Show only rejected articles (".$params['city'].")</a>\n";
		
		$pagetitle="Magazine (".$params['city']."): ".$total." articles found ";
		$this->load_view('admin/mag/list', $data, $pagetitle);
	}
	
	function drafts () {
		$params=$this->_GenerateSessionParams ();
		
		$data['city_url']=base_url().'admin/mag/drafts/';

		$where = (isset($params['fid']) && $params['fid'])
			  ? array('fid' => $params['fid'])
			  : array();
		
		$where['articles.publish_status']='1';
		
		$full_listing=$this->mag->fetchArticles_forCMS ($this->standard_select, $where, 'articles.aid DESC');
		
		if ($full_listing) {
			$total=$full_listing->num_rows();
			
			$start = $this->_GeneratePagination ('/admin/mag/drafts/page/', $total);
	
			$data['listing']=$this->mag->fetchArticles_forCMS ($this->standard_select, $where, 'articles.position DESC', 15, $start);
			$data['pagination']=$this->pagination->create_links();
		} else {
			$total=0;
			$data['listing']=false;
		}
		
		$data['extra_links']="<a href=\"/admin/mag/listing\" title=\"Show all articles (".$params['city'].")\">Show all articles (".$params['city'].")</a> | <a href=\"/admin/mag/rejected/\" title=\"Show only rejected articles (".$params['city'].")\">Show only rejected articles (".$params['city'].")</a>\n";
		
		$pagetitle="Magazine (".$params['city']."): ".$total." DRAFT articles found";
		$this->load_view('admin/mag/list', $data, $pagetitle);
	}
	
  function rejected () {
		$params=$this->_GenerateSessionParams ();

		$data['city_url']=base_url().'admin/mag/rejected/';
		
		$where = (isset($params['fid']) && $params['fid'])
			  ? array('fid' => $params['fid'])
			  : array();
		
		$where['articles.publish_status']='0';
		
		$full_listing=$this->mag->fetchArticles_forCMS ($this->standard_select, $where, 'articles.aid DESC');
		
		if ($full_listing) {
			$total=$full_listing->num_rows();

			$start = $this->_GeneratePagination ('/admin/mag/rejected/page/', $total);
			
			$data['listing']=$this->mag->fetchArticles_forCMS ($this->standard_select, $where, 'articles.position DESC', 15, $start);;
			$data['pagination']=$this->pagination->create_links();
		} else {
			$total=0;
			$data['listing']=false;
		}
		
		$data['extra_links']="<a href=\"/admin/mag/listing\" title=\"Show all articles (".$params['city'].")\">Show all articles (".$params['city'].")</a> | <a href=\"/admin/mag/drafts/\" title=\"Show only draft articles (".$params['city'].")\">Show only draft articles (".$params['city'].")</a>\n";
		
		$pagetitle="Magazine (".$params['city']."): ".$total." REJECTED articles found";
		$this->load_view('admin/mag/list', $data, $pagetitle);
	}

function add_new () {
	$data['action']='1'; //adding a new record
	$data['body_img_path']=date("Y-m-d");
	$data['front_page']=true;
				
				$_SESSION['ckfinder_baseUrl']= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
				$_SESSION['ckfinder_baseUrl'] .= "://".$_SERVER['HTTP_HOST'];
				$_SESSION['ckfinder_baseUrl'] .= "/media/magazine/body/".$data['body_img_path']."/";
				
				$_SESSION['ckfinder_baseDir'] = $_SERVER['DOCUMENT_ROOT']."/media/magazine/body/".$data['body_img_path']."/";

	$pagetitle="Magazine: add new article";
	
	$this->adminlayout->set('js', '/includes/ckeditor/ckeditor.js');
    $this->adminlayout->set('js', '/includes/ckfinder/ckfinder.js');
    
	$this->load_view('admin/mag/article', $data, $pagetitle);
}

function edit ($current_record_id=false) {
	if ($current_record_id) {

		$data['article_info']=$this->mag->fetchArticles_forCMS ('articles.*, articles_fp.aid AS featured, GROUP_CONCAT(categoriesRelated.cat_id SEPARATOR \'+\') AS related_categories', array ('articles.aid' => ($current_record_id*1)), false, 1, 0);
		
		if ($data['article_info']->num_rows()==1) {
				
				$data['article_info'] = $data['article_info']->row_array();
			
				$data['action']='2';
		
				$data['related_categories']=explode('+', $data['article_info']['related_categories']);
				$data['related_fids']=$this->mag->GetArticlesFids ($current_record_id);
				$data['festival_status']=$this->mag->get_festival_entry ($current_record_id);
				
				$_SESSION['ckfinder_baseUrl']= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
				$_SESSION['ckfinder_baseUrl'] .= "://".$_SERVER['HTTP_HOST'];

				if (!empty($data['article_info']['body_img_path']) && strlen($data['article_info']['body_img_path'])>0) {
					$_SESSION['ckfinder_baseDir'] = $_SERVER['DOCUMENT_ROOT']."/media/magazine/body/".$data['article_info']['body_img_path']."/";
					$_SESSION['ckfinder_baseUrl'] .= "/media/magazine/body/".$data['article_info']['body_img_path']."/";
				} else {
					$_SESSION['ckfinder_baseDir'] = $_SERVER['DOCUMENT_ROOT']."/media/additional/";
					$_SESSION['ckfinder_baseUrl'] .= "/media/additional/";
				}
			
			/* related */
			$related_articles = $this->mag->retrieveRelatedMagazineLive ($current_record_id);
			$related_articles=(!$related_articles)
				 ? array()
				 : $related_articles->result_array();

			$related_videos = $this->mag->retrieveRelatedVideosLive ($current_record_id);
			$related_videos=(!$related_videos)
				? array()
				: $related_videos->result_array();

			$data['related_articles']=array_merge($related_articles, $related_videos);
			/* end of related */
			
			$data['uid_uploaded']=$this->usermodel->get($data['article_info']['uid']);
			$data['uid_edited']=$this->usermodel->get($data['article_info']['uid_edit']);

			$this->adminlayout->set('js', '/includes/ckeditor/ckeditor.js');
   			$this->adminlayout->set('js', '/includes/ckfinder/ckfinder.js');

			$this->load_view('admin/mag/article', $data, "Magazine: ".$data['article_info']['title']);

		} else {
			$this->listing ();
		}
	} else {
		$this->listing ();
	}
}

function delete_article ($aid) {
	if ($this->usermodel->CanAdminDelete ($this->auth->currently_loggedin)==true) {
		$a_info=$this->mag->getArticle ($aid, true);
		if ($a_info) {
			if ($this->mag->deleteArticle($aid)) { $this->_DeleteArticlesImages ($a_info); }
		}
		redirect (base_url()."admin/mag/");
	} else {
		echo "<script language=\"javascript\">
					alert('Operation could not be completed as you do not have enough permissions to delete the content. Please contact your local web developer.');
					window.location='/admin/mag/';
			  </script>";
	}
}

  function process ($current_record_id=false) {
  	
  	foreach ($_POST as $key => $value) { $data_to_db[$key]=$value; }

	if (!empty($data_to_db['title'])) {
  
		  	if (isset($data_to_db['uploaded_media']) && !empty($data_to_db['uploaded_media'])) {
		  		
		  		$data_to_db['headliner']=$data_to_db['uploaded_media'];
		  		
				  	if (isset($data_to_db['new_thumbnail']) &&
					  	  isset($data_to_db['x1']) && is_numeric($data_to_db['x1']) &&
					  	  isset($data_to_db['w']) && is_numeric($data_to_db['w']) &&
					  	  isset($data_to_db['y1']) && is_numeric($data_to_db['y1']) &&
					  	  isset($data_to_db['h']) && is_numeric($data_to_db['h'])) {
					  	  	
					  	  	$thumb_in_action=$_SERVER['DOCUMENT_ROOT']."/media/magazine/220x132/".$data_to_db['headliner'];
					  	  	if (strlen($data_to_db['headliner'])>0 && file_exists($thumb_in_action)) {
					  	  		unlink ($thumb_in_action);
					  	  	}
					  	  	
					  	  	$scale = (220/$data_to_db['w']);
		
					  	  	$cropped = $this->a_media_upload->resizeThumbnailImage($thumb_in_action, $_SERVER['DOCUMENT_ROOT']."/media/magazine/604x/".$data_to_db['headliner'], $data_to_db['w'],$data_to_db['h'],$data_to_db['x1'],$data_to_db['y1'],$scale);
					  	
					    }
		  	}
	
		$data_to_db['featured']=(isset($data_to_db['featured']) && $data_to_db['featured']=='true')
				? "true"
				: "false";
	
		$data_to_db['mailout']=(isset($data_to_db['mailout']) && $data_to_db['mailout']=='true')
				? "true"
				: "false";
		
		$data_to_db['timestamp_publish']=(!empty($data_to_db['timestamp_publish']) && strlen($data_to_db['timestamp_publish'])==10)
			? strtotime(date_to_mysql($data_to_db['timestamp_publish']))
			: time();
		
		if ($current_record_id) { //Editing only!!!
			$insertation=$this->mag->updateArticle($data_to_db, $current_record_id);
	
			if (!empty($insertation)) {
				$info_msg="Information about the article has been changed | <a href=\"/admin/mag/edit/".$insertation."\">Back to the updated article</a>";	
			}

		} elseif (!$current_record_id) {
			$insertation=$this->mag->updateArticle($data_to_db);
		
			if (!empty($insertation)) {
				$this->mag->GenerateRelatedInfo ($insertation, $data_to_db['tags']);
				$info_msg="Your article has succesfully been submitted | <a href=\"/admin/mag/edit/".$insertation."\">Click here to make changes</a>";
			}
		}
		
		/* Updating the categories and franchises */
		if (isset($data_to_db['extra_categories'])) {
			
			if (!in_array($data_to_db['primary_category'], $data_to_db['extra_categories'])) {
				$data_to_db['extra_categories'][count($data_to_db['extra_categories'])]=$data_to_db['primary_category'];
			}
	
		} else {
			$data_to_db['extra_categories'][0]=$data_to_db['primary_category'];
		}
		
		if (isset($data_to_db['extra_fids'])) {
			
			if (!in_array($data_to_db['primary_fid'], $data_to_db['extra_fids'])) {
				$data_to_db['extra_fids'][count($data_to_db['extra_fids'])]=$data_to_db['primary_fid'];
			}
	
		} else {
			$data_to_db['extra_fids'][0]=$data_to_db['primary_fid'];
		}
		
		$this->mag->UpdateCategoriesConnections ($insertation, $data_to_db['extra_categories']);
		$this->mag->UpdateFranchiseConnections ($insertation, $data_to_db['extra_fids']);
		
		/* Working with festival promotions */
		if ($data_to_db['festival_status']) {
			switch ($data_to_db['festival_status']) {
				case 'false':
					//delete the festival entry if editing is present
					if ($current_record_id) {
						$this->mag->delete_festival ($insertation);
					}
					break;
				case 'guide':
					$festData=array ('aid' => $insertation, 'type' => 'guide');
					$this->mag->register_festival ($insertation, $festData);
					break;
				case 'event':
					$festData=array ('aid' => $insertation,	'type' => 'event');
					$this->mag->register_festival ($insertation, $festData);
					break;	
			}
		}
		/* end of festivals */
		
		$this->auth->extend_users_session (array('cms_post_action_message' => $info_msg));
		
		redirect (base_url()."admin/mag/listing");
	} else {
		redirect (base_url()."admin/mag/listing");
	}
 }
 
 function load_view($view, $data, $pageHeadline=false) {
	$pageHeadline=($pageHeadline) ? $pageHeadline : "Magazine";
	$this->adminlayout->set('pageName', $pageHeadline);

	$this->adminlayout->set('css', '/includes/js/jquery.imgareaselect-0.9.8/css/imgareaselect-default.css');
	$this->adminlayout->set('js', '/includes/js/media.js');
	$this->adminlayout->set('js', '/includes/js/carousel.js');
	$this->adminlayout->set('js', '/includes/js/jquery.imgareaselect-0.9.8/scripts/jquery.imgareaselect.min.js');
	$this->adminlayout->set('js', '/includes/js/form_validator/gen_validatorv31.js');
	
	if ($view==$this->views['contributors']) {	
		$this->adminlayout->set('js', '/includes/ckeditor/ckeditor.js');
		$this->adminlayout->set('js', '/includes/ckfinder/ckfinder.js');
	}

	$data['franchises']=$this->franchisemodel->getFranchisesByCountry(ACTIVE_COUNTRY, true);
	$data['categories']=$this->commonlib->categories_id;

	$data['featured'] = array (
		'true' => 'Yes - display in the front page',
	   	'false' => 'No - do not dipslay in a front page'
	);

	$data['publish_status'] = array (
		'0' => 'Rejected - marked for deletion',
	   	'1' => 'Draft',
		'2' => 'Live',
	);

	$this->adminlayout->set('body', $data, $view);
	$this->adminlayout->loadAdmin();
}

  private function _LatestTabs_Query ($fid) {
		return $this->mag->fetch_articles ('articles.aid, articles.title, articles.title_url, articles.intro_txt, articles.headliner, SUBSTRING(articles.content,1,200) AS content,
												 categories.name AS categoryName, categories.shortname AS categoryShortname,
												 franchises.name AS cityName, franchises.shortname AS cityShortname',
												 true, true,
												 array('articles.publish_status' => '2', 'articles.mailout' => 'true', 'articles.fid' => $fid),
												 false, false,
												 'articles.position DESC',
												 6, 0);
	}
	
	function newsletter () {
		$this->load->model('mpackmodel');
		$this->load->model('dptvminimodel', 'a_dptvmodel');
		$this->load->model('posterminimodel');
	  	$this->load->model('wincompmodel', 'win');
	
	  	$this->load->model('designcomp/designcompmodel', 'designcomp');
	  	
	  	$this->load->helper('text');
	
	 	if ($this->input->post('newsletter_generator', TRUE)) {
	 		/*
	 		//User chosen skin
	 		if ($this->input->post('skin_to_apply', TRUE) && $this->input->post('skin_to_apply', TRUE)!=='0') {
	 			$this->load->model('skinsmodel');
	 			$data['skin']=$this->skinsmodel->get_skin ($this->input->post('skin_to_apply', TRUE), false);
	 		} else {
	 			$data['skin']=false;
	 		}*/
	 		
	 		//forced skin
	 		//$this->load->model('skinsmodel');
 			//$data['skin']=$this->skinsmodel->get_skin (133, false);
	 			
		 	$fid=($this->input->post('fid', TRUE)) ? ($this->input->post('fid', TRUE)*1) : '1';
		 	$params['mailout']='true';
		 	
		 	$data['city']=$this->franchisemodel->get($fid);
		 	
			$array=$this->_LatestTabs_Query ($fid);

		 	/*$array=array ();
			foreach ($this->commonlib->categories_id AS $key => $value) {

				$array[$value]=$this->_LatestTabs_Query ($key, $fid); //should be $array[$i]=array (article0, article1)

				if ($array[$value]==false) {
					$array[$value]=$this->_LatestTabs_Query ($key, '1');
				}

			}*/
			
			$data['articles']=$array;
		
			/* Right hand photo strip */
			$mpack = $this->mpackmodel->get_fp_photos(5);
			if ($mpack) {
				for ($i=0; $i!==count($mpack); $i++) {
					$mpack[$i]['src'] = base_url()."media/mpack/photos/106x100/".$mpack[$i]['filename'];
				}
			}

		 	$data['random_pics']=$mpack;
	 		/* End of right hand photo strip */	
	 		
	 		/* Fetching the active competitions for the front page */
	 		$where = array (
	  			'date_live <= '	=> date("Y-m-d"),
	  			'date_vote_end >= ' => date("Y-m-d"),
	  			'active'	=> 'true'
	  		);
	  		
	  	  	$data['active_comp_list']=$this->designcomp->retrieve_list ($where, 'date_vote_end DESC', 0, -1, 'designcompID, sectionID, title, handle, headliner_fp');
		  	
		  	/* Fetching active promotions
	  		$promos=$this->promominimodel->get_list_of_records ('current', 'sort_order DESC, start_date DESC', 8, 'm', false, false, false, false, false, $fid);
	  	 
	 		foreach ($promos->result_array() AS $row) { $data['promos'][]=$row; } */
			
		  	/* Fetching WIN promotions */
		  	$win_widget_list=$this->win->get_fp_records($this->settingsmodel->settings['WIN_BANNERS_FP_TOTAL']);
	 		if ($win_widget_list) { foreach ($win_widget_list->result_array() AS $row) { $data['win'][]=$row; } }
	 		
	 		/* Fetching the last DPTV */
	 		$data['latest_video_right']=$this->a_dptvmodel->get_latest_video();
	 		
		 	/* Fetching the last poster */
			$data['latest_poster']=$this->posterminimodel->get_latest_poster();
			
			$data['leaderboard']=$this->adsmodel->GetBanner ('mag_mailer', $fid, 'lboard');
			$data['skyscraper']=$this->adsmodel->GetBanner ('mag_mailer', $fid, 'sky');
			$data['mpu']=$this->adsmodel->GetBanner ('mag_mailer', $fid, 'mpu');
		 	
			$final = array (
				'newsletter_gen'  => $this->newsletter_generator_view_output (),
				'newsletter_code' => $this->load->view('/admin/mag/newsletter_code', $data, true),
				'fid' 			  => $fid,
				'front_end_link'  => base_url().'magazine/newsletter/'.$data['city']['shortname']
			);
			
			$this->adminlayout->set('pageName', 'Magazine newsletter');
			$this->adminlayout->set('js', '/includes/js/media.js');
			$this->adminlayout->set('body', $final, '/admin/mag/newsletter_preview');
			$this->adminlayout->loadAdmin();
		 	
	 	} else { 

	 		$final = array (
				'newsletter_gen'  => $this->newsletter_generator_view_output ()
	 		);
	 		
			$this->adminlayout->set('pageName', 'Magazine newsletter');
			$this->adminlayout->set('js', '/includes/js/media.js');
			$this->adminlayout->set('body', $final, '/admin/mag/newsletter_preview');
			$this->adminlayout->loadAdmin();
	 	}
	 }
	 
	 private function newsletter_generator_view_output () {
			$this->load->model('a_photomodel');
	 		$data['franchises']=$this->franchisemodel->getFranchisesByCountry(ACTIVE_COUNTRY, true);
	 		
	 		$random_pics=$this->a_photomodel->get_list_of_records (false, false, 40, 0);
	 		foreach ($random_pics->result_array() AS $row) { $data['photo_selection'][$row['photoID']]=$row['title']; }
	 			 		
	 		$this->load->model('skinsmodel');
	 		
	 		$data['skins']  = $this->skinsmodel->get_list(false, 'id DESC', 0, -1, 'id, skin_name', true);
	 		
	 		if ($data['skins']) {
    			$data['skins'][0]='N/A';
    		}
    		
	 		return $this->load->view ('/admin/mag/newsletter_gen', $data, TRUE);
	 }
	 
	 function newsletter_preview () {
	 		if ($this->input->post('mag_newsletter_preview_from_code_page', TRUE) && $this->input->post('mag_newsletter_code')) {
	 			echo $this->input->post('mag_newsletter_code');
	 		} else {
	 			redirect (base_url().'mag/newsletter');	
	 		}
	 }
	 
	 function record_newsletter () {
	 	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
		 	$data['fid']=($this->input->post('fid')*1);
		 	$data['content']=$this->input->post('content');
		 	
		 	if ($data['fid'] && $data['content']) {
		 		$data['date']=date("Y-m-d");
		 		$this->mag->record_newsletter ($data);
		 		echo 'true';	
		 	} else {
		 		echo 'false';	
		 	}
	 	}
	 }	 
	 	 
	 

  } // END CLASS MAGAZINE CONTROLLER
?>