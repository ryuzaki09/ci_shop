<?php

  class Events extends CI_Controller  {

  	var $sort_ordersASC = array (
		'name' => 'name ASC',
		'categ' => 'category_id ASC',
  		'dates' => 'eventdate ASC',
		'views' => 'views ASC'
	);

	var $sort_ordersDESC = array (
		'name' => 'name DESC',
		'categ' => 'category_id DESC',
		'dates' => 'eventdate DESC',
		'views' => 'views DESC'
	);
	
	var $img_upload_window_atts = array(
              'width'      => '660',
              'height'     => '360',
              'scrollbars' => 'yes',
              'status'     => 'no',
              'resizable'  => 'no',
              'screenx'    => '100',
              'screeny'    => '100'
            );
    
    var $status_msg = array (
		'1' => 'Your event has been uploaded',
		'2' => 'Your event has been changed',
		'3' => 'Your event has been deleted'
	);

  function __construct() {
     parent::__construct();
  	 $this->auth->restrict_backend ();
  	 $this->load->library('adminlayout');
     $this->load->model('eventsmodel', 'events');
     $this->load->model('venuesmodel', 'venues');
	 $this->load->model('franchisemodel');
     $this->load->library('a_media_upload');
  }
  
  function carousel_crop ($eventID) {
  	$data['event_info']=$this->events->getEvent ($eventID, true, 'events.eventsID, events.headliner', false);
  	
  	if ($data['event_info'] && !empty($data['event_info']['headliner'])) {
  		
	  	$data['form_link']='/admin/events/carousel_crop/'.$eventID;
	  	$data['source_img']='/media/events/604x/'.$data['event_info']['headliner'];
	  	$data['filename']=$data['event_info']['headliner'];
	  	
	  	if ($this->input->post('submit_crop', TRUE)) {
	  		
	  		if (is_numeric($this->input->post('x1')) &&
				is_numeric($this->input->post('w')) &&
				is_numeric($this->input->post('y1')) &&
				is_numeric($this->input->post('h'))) {
								  	  	
								  	  	$thumb_in_action=$_SERVER['DOCUMENT_ROOT']."/media/events/604x400/".$this->input->post('current_img');
								  	  	if (strlen($this->input->post('current_img'))>0 && file_exists($thumb_in_action)) {
								  	  		unlink ($thumb_in_action);
								  	  	}
								  	  	
								  	  	$scale = (604/$this->input->post('w'));
					
								  	  	$cropped = $this->a_media_upload->resizeThumbnailImage($thumb_in_action, $_SERVER['DOCUMENT_ROOT']."/media/events/604x/".$this->input->post('current_img'),
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
  	$fileUpload=$this->a_media_upload->catch_file ('media_filename', $_SERVER['DOCUMENT_ROOT']."/media/events/original_files/");
  	
  	if (($fileUpload['attempt'] && $fileUpload['result']) || isset($_POST['media_file_www']) && strlen($_POST['media_file_www'])>3) {

  			  $success=false;
			
  		      if (($fileUpload['attempt'] && $fileUpload['result'])) {
				  $this->place_picture_files ($fileUpload['filename']);
				  	
				  $data_to_db['headliner']=$fileUpload['filename'];
				  $data_to_db['original_filename']=$fileUpload['filename'];
				  
				  $success=true;
				  
  		      } elseif (isset($_POST['media_file_www'])) {
  		      	  $copied_image = $this->a_media_upload->copy_img_from_www ($_POST['media_file_www'], $_SERVER['DOCUMENT_ROOT'].'/media/events/original_files/');
  		      	  
  		      	  if ($copied_image) {
	  		      	  $this->place_picture_files ($copied_image);
					  	
					  $data_to_db['headliner']=$copied_image;
					  $data_to_db['original_filename']=$copied_image;
					  
					  $success=true;
  		      	  }
  		      }
  		      	
			  if ($success) { ?>
					<input type="button" value="Finish" onclick="javascript: sendValue ('<?php echo $data_to_db['headliner']; ?>', '<?php echo $data_to_db['original_filename']; ?>');" />
					<script language="JavaScript">
						function sendValue (filename, original) {

							window.opener.$("#imagery").show();
							
							window.opener.document.getElementById('thumbnail').src = "/media/events/604x/" + filename;
							window.opener.document.getElementById('current_thumbnail').src = "/media/events/thumb/" + filename;
							window.opener.document.getElementById('t_preview').src = "/media/events/604x/" + filename;

							window.opener.document.getElementById('uploaded_media').value=filename;
							window.opener.document.getElementById('uploaded_media_original').value=original;
							
							window.close();
						}
					</script>
					
	<?php
		   }
	  	} else {
	  		$data['submit_url']=base_url().'admin/events/imgupload';
	  		$this->load->view ('admin/common/img_upload', $data);
	  	}
  }
    
  private function place_picture_files ($filename) {
  		$entryFilePaths = array (
			'full_path'		=> $_SERVER['DOCUMENT_ROOT']."/media/events/original_files/".$filename,
  			'604x'			=> $_SERVER['DOCUMENT_ROOT']."/media/events/604x/".$filename,
			'thumb_dest'	=> $_SERVER['DOCUMENT_ROOT']."/media/events/thumb/".$filename
		);	

		$this->a_media_upload->copy_uploaded_file ($entryFilePaths['full_path'], $entryFilePaths['604x'], 604);
		$this->a_media_upload->crop_snapshot ($entryFilePaths['full_path'], $entryFilePaths['thumb_dest'], 190, 132);
 	}

  private function delete_picture_files ($event_info) {
  	if (!empty($event_info['original_filename']) && !empty($event_info['headliner'])) {
		$path[0]=$_SERVER['DOCUMENT_ROOT']."/media/events/original_files/".$event_info['original_filename'];
		$path[1]=$_SERVER['DOCUMENT_ROOT']."/media/events/604x/".$event_info['headliner'];
		$path[2]=$_SERVER['DOCUMENT_ROOT']."/media/events/thumb/".$event_info['headliner'];
		$path[3]=$_SERVER['DOCUMENT_ROOT']."/media/events/604x400/".$event_info['headliner'];
		for ($i=0; $i!==count($path); $i++) {
			if (file_exists($path[$i])) { unlink ($path[$i]); }
		}
	}
  }

  function _SortOrder () {
  	$sort=$this->input->get('sort');
	  if ($sort && array_key_exists($sort, $this->sort_ordersASC)) {

			if (isset($_SESSION['dp_events_sort'])) {
				if ($_SESSION['dp_events_sort']==$this->sort_ordersASC[$sort]) {
					$_SESSION['dp_events_sort']=$this->sort_ordersDESC[$sort];
				} else {
					$_SESSION['dp_events_sort']=$this->sort_ordersASC[$sort];
				}

				$event_params['order_by'] = $_SESSION['dp_events_sort'];
	
			} else {
				$event_params['order_by'] = $_SESSION['dp_events_sort'] = $this->sort_ordersASC[$sort];
			}

			$_SESSION['dp_events_sort_display'] = $sort;
			
	  } else {
			if ($sort=='reset') {
				unset ($_SESSION['dp_events_sort']);
				unset ($_SESSION['dp_events_sort_display']);
				$event_params['order_by']="active DESC, eventdate ASC";
			} else {
		  		if (isset($_SESSION['dp_events_sort'])) {
					$event_params['order_by']=$_SESSION['dp_events_sort'];
				} else {
					$event_params['order_by']="active DESC, eventdate ASC";
				}
			}
	  }

	  return $event_params['order_by'];	  
  }
  
  function expired ($fid=false) {
  		$event_params['end-date']=date("Y-m-d", time()-86400);

		if (!$fid) {
			$default=$this->franchisemodel->getDefaultInCountry(ACTIVE_COUNTRY);
			$fid=$default->id;
			$current_city=$default->name;
		} else {
			$current_city=$this->franchisemodel->getName($fid);
		}

		$event_params['franchise']=$data['franchise']=$fid;
  		$event_params['order_by']=$this->_SortOrder ();
  		
		$full_list=$this->events->retrieve($event_params, null, null, false);
		$page = $this->uri->segment(get_page_segment_number());
	
		$data['listing']=$this->events->retrieve($event_params, 100, $page, false);
		$data['action']='1';

		$data['total']=$full_list->num_rows();
	
		$this->load->library('pagination');
				$removeParts = array ('st');
				$config['base_url'] = ($fid && is_numeric($fid))
					? "/admin/events/expired/".$fid."/page/"
					: "/admin/events/expired/page/";
				
				$config['total_rows'] = $data['total'];
				$config['per_page']   = 30;
				$config['page_query_string'] 	= FALSE;
				$config['uri_segment'] 			= get_page_segment_number();
				$config['cur_tag_open'] 		= '&nbsp;<a class="ui-state-active" href="javascript: void(0);">';
				$config['cur_tag_close'] 		= '</a>';
				$config['num_links'] = 4;
		$this->pagination->initialize($config);
	
		$data['cities']=$this->franchisemodel->getFranchisesByCountry(ACTIVE_COUNTRY);
	
		$this->load_view('admin/events/expired_list', $data, "Expired ".$current_city." events: ".$data['total']);
  }

  function showchoice ($fid=false) {    
  	$event_params['start-date']=date("d/m/Y", time());
  	
    if (!$fid) {
		$default=$this->franchisemodel->getDefaultInCountry(ACTIVE_COUNTRY);
		$fid=$default->id;
		$current_city=$default->name;
	} else {
		$current_city=$this->franchisemodel->getName($fid);
	}
	
	$event_params['franchise']=$data['franchise']=$fid;
	
	$event_params['order_by']=$this->_SortOrder ();
  	
	$full_list=$this->events->retrieve($event_params, null, null, "(promotion='CHOICE' OR promotion='CHOICE_FP')");
	
	$page = $this->uri->segment(get_page_segment_number());

	$data['listing']=$this->events->retrieve($event_params, 30, $page, "(promotion='CHOICE' OR promotion='CHOICE_FP')");
	$data['action']='1';
	
	$data['total']=$full_list->num_rows();

	$this->load->library('pagination');

			$config['base_url'] = ($fid)
				? base_url()."admin/events/showchoice/".$fid."/page/"
				: base_url()."admin/events/showchoice/page/";
				
			$config['total_rows'] = $data['total'];
			$config['per_page']   = 30;
			$config['page_query_string'] 	= FALSE;
			$config['uri_segment'] 			= get_page_segment_number();
			$config['cur_tag_open'] 		= '&nbsp;<a class="ui-state-active" href="javascript: void(0);">';
			$config['cur_tag_close'] 		= '</a>';
			$config['num_links'] = 4;
	$this->pagination->initialize($config);
	$data['pagination']=$this->pagination->create_links();

	$data['cities']=$this->franchisemodel->getFranchisesByCountry(ACTIVE_COUNTRY);

	$this->load_view('admin/events/events_list', $data, "Upcoming ".$current_city." choice events: ".$data['total']);
 }

 function showpublic ($fid=false) {
 	$event_params['start-date']=date("d/m/Y", time());

 	if (!$fid) {
		$default=$this->franchisemodel->getDefaultInCountry(ACTIVE_COUNTRY);
		$fid=$default->id;
		$current_city=$default->name;
	} else {
		$current_city=$this->franchisemodel->getName($fid);
	}

	$event_params['franchise']=$data['franchise']=$fid;
	$event_params['order_by']=$this->_SortOrder ();


	$full_list=$this->events->retrieve($event_params, null, null, "(promotion='PUBLIC' OR promotion='PUBLIC_HGHLT')");
	
	$page = $this->uri->segment(get_page_segment_number());

	$data['listing']=$this->events->retrieve($event_params, 30, $page, "(promotion='PUBLIC' OR promotion='PUBLIC_HGHLT')");
	$data['action']=2;
	
	$data['info_msg']=($this->input->get('status')) ? $this->status_msg[$this->input->get('status')] : false;

	$data['total']=$full_list->num_rows();

	$this->load->library('pagination');

			$config['base_url'] = ($fid)
				? base_url()."admin/events/showpublic/".$fid."/page/"
				: base_url()."admin/events/showpublic/page/";
				
			$config['total_rows'] = $data['total'];
			$config['per_page']   = 30;
			$config['page_query_string'] 	= FALSE;
			$config['uri_segment'] 			= get_page_segment_number();
			$config['cur_tag_open'] 		= '&nbsp;<a class="ui-state-active" href="javascript: void(0);">';
			$config['cur_tag_close'] 		= '</a>';
			$config['num_links'] = 4;
			
	$this->pagination->initialize($config);
	$data['pagination']=$this->pagination->create_links();

	$data['cities']=$this->franchisemodel->getFranchisesByCountry(ACTIVE_COUNTRY);

	$this->load_view('admin/events/events_list', $data, "Upcoming ".$current_city." public events: ".$data['total']);
 }
 
 function add_new () {
	$data['action']='1'; //adding a new record
	$data['img_popup']=$this->img_upload_window_atts;
	$this->load_view('admin/events/form', $data, "Fill the form below to add new event");
 }

function edit ($current_record_id=false) {
	if ($current_record_id) {
		$data['photo_info']=$this->events->getEvent ($current_record_id, true);

		if ($data['photo_info']) {
			$data['action']='2'; //editing a particular record
			$data['img_popup']=$this->img_upload_window_atts;
			
			$data['uid_uploaded']=$this->usermodel->get($data['photo_info']['uid']);
			$uid_edited=$this->usermodel->get($data['photo_info']['uid_edit']);
			
			$data['last_edited']=(!empty($data['photo_info']['timestamp_edit']))
			  	? "Last edited on: <b>".date("d - M - Y H:i", $data['photo_info']['timestamp_edit']). "</b> by: <b>".$uid_edited['uname']." (".$uid_edited['email'].")</b>\n"
			    : "Not edited yet";		
			
			$this->load_view('admin/events/form', $data, "Event: ".$data['photo_info']['name']);
		} else {
			$this->showchoice(false);
		}
	} else {
		$this->showchoice(false);
	}
}

  function process ($current_record_id=false) {
	foreach ($_POST as $key => $value) { $data_to_db[$key]=trim($value); }

	if (!empty($data_to_db['name'])) {
	
	  $data_to_db['name']=htmlspecialchars($data_to_db['name'], ENT_QUOTES);
	  $data_to_db['string_id']=strtolower(url_title($data_to_db['name']));

	 /* Proccesing image upload if the image is being uploaded */  
	 	 if (!empty($data_to_db['uploaded_media'])) {
		  		
		  		$data_to_db['headliner']=$data_to_db['uploaded_media'];
		  		$data_to_db['original_filename']=$data_to_db['uploaded_media_original'];
		  		
		  		
				  	if (isset($data_to_db['new_thumbnail']) &&
					  	  isset($data_to_db['x1']) && is_numeric($data_to_db['x1']) &&
					  	  isset($data_to_db['w']) && is_numeric($data_to_db['w']) &&
					  	  isset($data_to_db['y1']) && is_numeric($data_to_db['y1']) &&
					  	  isset($data_to_db['h']) && is_numeric($data_to_db['h'])) {
					  	  	
					  	  	$thumb_in_action=$_SERVER['DOCUMENT_ROOT']."/media/events/thumb/".$data_to_db['headliner'];
					  	  	if (strlen($data_to_db['headliner'])>0 && file_exists($thumb_in_action)) {
					  	  		unlink ($thumb_in_action);
					  	  	}
					  	  	
					  	  	$scale = (190/$data_to_db['w']);
		
					  	  	$cropped = $this->a_media_upload->resizeThumbnailImage($thumb_in_action, $_SERVER['DOCUMENT_ROOT']."/media/events/604x/".$data_to_db['headliner'], $data_to_db['w'],$data_to_db['h'],$data_to_db['x1'],$data_to_db['y1'],$scale);
			  		
					    }
		  	}
		  	
		  					unset ($data_to_db['new_thumbnail']);
					  		unset ($data_to_db['uploaded_media']);
					  		unset ($data_to_db['uploaded_media_original']);
					  		unset ($data_to_db['x1']);
					  		unset ($data_to_db['x2']);
					  		unset ($data_to_db['y1']);
					  		unset ($data_to_db['y2']);
					  		unset ($data_to_db['w']);
					  		unset ($data_to_db['h']);
	 /* End of image proccesing */
	  

	/* Processing the dates */
	if (!empty($data_to_db['eventdate'])) { $data_to_db['eventdate']=$this->commonlib->TransformDate1 ($data_to_db['eventdate']); }

	$data_to_db['eventdate_last']=(!empty($data_to_db['eventdate_last']) && $data_to_db['eventdate_last']!=='0000-00-00')
		  ? $this->commonlib->TransformDate1 ($data_to_db['eventdate_last'])
	 	  : $data_to_db['eventdate_last']=$data_to_db['eventdate'];
	 
	if ($data_to_db['eventdate_last'] < $data_to_db['eventdate']) { $data_to_db['eventdate_last'] = $data_to_db['eventdate']; }

	$data_to_db['date_display_starts'] = (!empty($data_to_db['date_display_starts']) && $data_to_db['date_display_starts']!=='0000-00-00')
		      	? $this->commonlib->TransformDate1 ($data_to_db['date_display_starts'])
		      	: $data_to_db['date_display_starts']=date("Y-m-d", time());
	
	$data_to_db['date_display_ends'] = (!empty($data_to_db['date_display_ends']) && $data_to_db['date_display_ends']!=='0000-00-00')
		      	? $this->commonlib->TransformDate1 ($data_to_db['date_display_ends'])
		      	: $data_to_db['date_display_ends']=$data_to_db['eventdate_last'];
	
	if ($data_to_db['date_display_ends'] < $data_to_db['date_display_starts']) { $data_to_db['date_display_ends'] = $data_to_db['eventdate_last']; }
	/* End of dates' ops */
	
	$data_to_db['repeat_days']=$data_to_db['repeat_days']*1;

	$fid=$this->venues->getFID ($data_to_db['venueID']);
	$data_to_db['fid']=$fid['fid'];

	$data_to_db['website_takeover']=(isset($data_to_db['website_takeover']) && $data_to_db['website_takeover']=='true') ? "true" : "false";
	
	$data_to_db['mailout']=(isset($data_to_db['mailout']) && $data_to_db['mailout']=='true') ? "true" : "false";
	$data_to_db['dp_pack']=(isset($data_to_db['dp_pack']) && $data_to_db['dp_pack']=='true') ? "true" : "false";
	$data_to_db['price']=(isset($data_to_db['price'])) ? $data_to_db['price'] : "0.00";
	$data_to_db['active']=(isset($data_to_db['active']) && $data_to_db['active']=='true') ? "true" : "false";

	unset ($data_to_db['mysubmit']);

	if ($current_record_id) { //Editing only!!!
		$insertation=$this->events->update($data_to_db, $current_record_id);
		$status='2';
	} elseif (!$current_record_id) { //New record, must be with picture!!!
		$insertation=$this->events->create($data_to_db);
		$status='1';
	}
	
		$this->cache->delete_group('events_listing_');
		
		if ($data_to_db['eventdate_last']<date("Y-m-d")) {
			redirect (base_url()."admin/events/expired/".$data_to_db['fid']);
		} else {
			($data_to_db['promotion']=='CHOICE' || $data_to_db['promotion']=='CHOICE_FP')
				? redirect (base_url()."admin/events/showchoice/".$data_to_db['fid'])
				: redirect (base_url()."admin/events/showpublic/".$data_to_db['fid']);
			}
	} else {
		redirect (base_url()."admin/events/showchoice/");
	}
 }
 
 function delete ($record_id=false) {
 	if ($record_id) {
 		$event_info=$this->events->getEvent ($record_id, true);
 		
 		if ($event_info) {
		 		$redirection = base_url();
		 		if ($event_info['eventdate_last']<date("Y-m-d")) {
		 			$redirection .= "admin/events/expired/".$event_info['fid'];
		 		} else {
		 			$redirection .= ($event_info['promotion']=='CHOICE' || $event_info['promotion']=='CHOICE_FP') ? "admin/events/showchoice/".$event_info['fid'] : "admin/events/showpublic/".$event_info['fid'];
		 		}
	
		 		if ($this->usermodel->CanAdminDelete ($this->auth->currently_loggedin)==true) {
		 			if ($this->events->delete ($record_id)) {
		 				$this->delete_picture_files ($event_info);
		 				$this->cache->delete_group('events_listing_');
		 				
		 				$this->auth->extend_users_session (array('cms_post_action_message' => 'Your event has been deleted'));
		 						 				
		  				redirect ($redirection);	
		 			}
		 		} else {
		 			echo "<script language=\"javascript\">
		 					alert('Operation could not be completed as you do not have enough permissions to delete the content. Please contact your local web developer.');
		 					window.location='".$redirection."';
		 				</script>";
		 		}
 		} else {
 			redirect (base_url()."admin/events/showchoice/");
 		}
 	} else {
 		redirect (base_url()."admin/events/showchoice/");
 	}
 }
 
 function delete_event_ajax ($eventID) {
 	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')) {
	 	$event_info=$this->events->getEvent ($eventID, true);	
	 	if ($event_info) {
	 		if ($this->usermodel->CanAdminDelete ($this->auth->currently_loggedin)==true) {
	 			$this->events->delete ($eventID);
	 			$this->delete_picture_files ($event_info);
	 			$this->cache->delete_group('events_listing_');
	 			$result='true';
	 		} else {
	 			$result='false';	
	 		}

 		} else {
 			$result='false';	
 		}
 		
 		echo $result;
 	}
 }
 
 function delete_multi () {
 	$selection=$this->input->post('multiply_events');
 	if (isset($_POST['delete_selected']) && $selection) {
 		if ($this->usermodel->CanAdminDelete ($this->auth->currently_loggedin)==true) {
		 	for ($i=0; $i!==count($selection); $i++) {
		 		$event_info=$this->events->getEvent ($selection[$i], true);
		 			if ($this->events->delete ($selection[$i])) {
		 				$this->delete_picture_files ($event_info);
		 				$this->cache->delete_group('events_listing_');
		 			}
		 	}
		 	
		 	$this->auth->extend_users_session (array('cms_post_action_message' => count($selection).' events have been deleted'));
		 	
		 	redirect (base_url()."admin/events/expired/".$event_info['fid']."/?status=3");
 		} else {
 			$event_info=$this->events->getEvent ($selection[0], true);
 			echo "<script language=\"javascript\">
		 					alert('Operation could not be completed as you do not have enough permissions to delete the content. Please contact your local web developer.');
		 					window.location='/admin/events/expired/".$event_info['fid']."';
		 		  </script>";
 		}
 	} else {
 		redirect (base_url()."admin/events/expired/");
 	}
}

 function transactions () {
 	$all=$this->events->GetTransactions();
 	if ($all) {
 		for ($i=0; $i!==count($all); $i++) {
 			
 		}
 	}	
 }
 
  /* AJAX request function to search articles */
  function filtrate_events ($type=false) {
  		$frag=$this->input->post('key');
  		
  		if ($frag && strlen($frag)>1) {
  			$result=$this->events->performSearch ($frag, false, '0', 20, true);
  			if ($result) {
  				if ($type) {
  					switch ($type) {
  						case '1':

							foreach ($result AS $row) {
								$date_output=$this->commonlib->EventsDateOutputFP($row['eventdate'], $row['eventdate_last']);
								echo "<a href=\"/admin/events/edit/".$row['eventsID']."\">".$row['name']." | ".$date_output."</a> | <a href=\"/admin/events/delete/".$row['eventsID']."\" onclick=\"return confirm('Are you sure you want to delete this record? This can not be undone');\">DELETE</a><br />\n";
							}
						break;

  						case '2':
  							foreach ($result AS $row) {
								//echo "<a href=\"/admin/mag/edit/".$row['aid']."\">".$row['title']."</a><br />\n";
							}
  						break;
  					}
  				} 
  			}
  		}
  }
     
 function contributors ($since_time=false) {
 	$since_time=($since_time) ? $since_time : time()-1209600;

 		$data['users']=$this->events->get_contributors ($since_time);

	 	$data['users']=array_unique($data['users']);
	 		
	 	$this->adminlayout->set('pageName', 'Event contributors since '.date ('d M Y', $since_time));
	 	$this->adminlayout->set('body', $data, 'admin/events/contributors');
		$this->adminlayout->loadAdmin();
 }
 
 function approve ($eventsID) {
 	$this->events->update(array('active' => 'true'), ($eventsID*1), false);
 	redirect (base_url().'admin/events/edit/'.$eventsID);
 }

 function newsletter () {	
	$this->load->model('mpackmodel');
	$this->load->model('dptvminimodel', 'a_dptvmodel');

  	$this->load->model('wincompmodel', 'win');
  	$this->load->model('posterminimodel');

  	$this->load->model('designcomp/designcompmodel', 'designcomp');
  	$this->load->helper('text');

 	$start_date=$this->input->post('from', TRUE);
 	$end_date=$this->input->post('to', TRUE);

 	if ($start_date && $end_date) {
 		
 		/* skining
 		if ($this->input->post('skin_to_apply', TRUE) && $this->input->post('skin_to_apply', TRUE)!=='0') {
 			$this->load->model('skinsmodel');
 			$data['skin']=$this->skinsmodel->get ($this->input->post('skin_to_apply', TRUE));
 		} else {
 			$data['skin']=false;
 		}
 		end of skinning */

 		$this->load->model('skinsmodel');
 		$data['skin']=$this->skinsmodel->get_skin (137, false);
 		
	 	/* Standard parameters for all the events */
 		$fid=($this->input->post('fid', TRUE)) ? ($this->input->post('fid', TRUE)*1) : '1';
 		
 		$data['city']=$this->franchisemodel->get($fid);
 		$data['from']=$start_date;
	 	$data['to']=$end_date;

 		$params = array (
	 		'events.mailout' 	   => 'true',
	 		'events.active' 	   => 'true',
	 		'events.fid' 	       => ($fid*1),
	 		'events.eventdate >= ' => date_to_mysql ($start_date),
		    'events.eventdate <= ' => date_to_mysql ($end_date)
	 	);
	 	
	 	$categories=$this->commonlib->categories_id;
		$categories = array ('2' => $categories[2], '1' => $categories[1], '3' => $categories[3]);

		foreach ($categories AS $key => $value) {
			
			$data['listing'][$value]['choice']=$this->events->fetch_events ('events.*, v.name AS venue_name', false, ($params+array('events.category_id' => $key)),
																		   "(events.promotion='CHOICE' OR events.promotion='CHOICE_FP')",
																		   true, 'events.eventdate ASC', 36, 0);
		    
			$data['listing'][$value]['public']=$this->events->fetch_events ('events.*, v.name AS venue_name', false, ($params+array('events.category_id' => $key)),
																		   "(events.promotion='PUBLIC' OR events.promotion='PUBLIC_HGHLT')",
																		   true, 'events.eventdate ASC', 24, 0);
		}
		
		/* Right hand photo strip */
		$mpack = $this->mpackmodel->get_fp_photos(5);
		if ($mpack) {
			for ($i=0; $i!==count($mpack); $i++) {
				$mpack[$i]['src'] = base_url()."media/mpack/photos/106x100/".$mpack[$i]['filename'];
			}
		}

	 	$data['random_pics']=$mpack;
 		/* End of right hand photo strip */
	 	
 		/* Fetching the active competitions */
	 	$where = array (
	  		'date_live <= '		=> date("Y-m-d"),
	  		'date_vote_end >= ' => date("Y-m-d"),
	  		'active'			=> 'true'
	  	);
	  		
	  	$data['active_comp_list']=$this->designcomp->retrieve_list ($where, 'date_vote_end DESC', 0, -1, 'designcompID, sectionID, title, handle, headliner_fp');

	  	/* Fetching WIN promotions */
 		$win_widget_list=$this->win->get_fp_records($this->settingsmodel->settings['WIN_BANNERS_FP_TOTAL']);
	 	if ($win_widget_list) { foreach ($win_widget_list->result_array() AS $row) { $data['win'][]=$row; } }

 		/* Fetching the last DPTV */
 		$data['latest_video_right']=$this->a_dptvmodel->get_latest_video();

 		/* Fetching the last poster */
		$data['latest_poster']=$this->posterminimodel->get_latest_poster();
        
        
        //1 is london, 4 is manchester
		$data['leaderboard']=$this->adsmodel->GetBanner ('0', '4', 'lboard');
		$data['skyscraper']=$this->adsmodel->GetBanner ('0', '4', 'sky'); 		
		$data['mpu']=$this->adsmodel->GetBanner ('0', '4', 'mpu');
		//$data['leaderboard']=$this->adsmodel->GetBanner ('events_mailer', $fid, 'lboard');
        //$data['skyscraper']=$this->adsmodel->GetBanner ('events_mailer', $fid, 'sky');       
        //$data['mpu']=$this->adsmodel->GetBanner ('events_mailer', $fid, 'mpu');

	 	//$this->load->view('/admin/events/newsletter_white', $data);
	 	$this->load->view('/admin/events/newsletter', $data);
 	} else {
 		$this->load->model('skinsmodel');
 		
 		$this->adminlayout->set('pageName', 'Events newsletter generator');
		$this->adminlayout->set('js', '/includes/js/form_validator/gen_validatorv31.js');

    	$this->adminlayout->set('js', '/includes/js/media.js');

    	$data['franchises']=$this->franchisemodel->getFranchisesByCountry(ACTIVE_COUNTRY, true);
    	
    	$data['skins']=$this->skinsmodel->get_list(false, 'id DESC', 0, -1, 'id, skin_name', true);
    	if ($data['skins']) {
    		$data['skins'][0]='N/A';
    	}
    	
    	/*
 		$random_pics=$this->a_photomodel->get_list_of_records (false, false, 40, 0);
 		foreach ($random_pics->result_array() AS $row) { $data['photo_selection'][$row['photoID']]=$row['title']; } */

    	$this->adminlayout->set('body', $data, '/admin/events/newsletter_gen');
		$this->adminlayout->loadAdmin();
 	}
 }

	function load_view($view, $data, $pageHeadline=false) {
	 	$pageHeadline=($pageHeadline) ? $pageHeadline : "Events";
		$this->adminlayout->set('pageName', $pageHeadline);

		$this->adminlayout->set('js', '/includes/js/form_validator/gen_validatorv31.js');
		
		$this->adminlayout->set('js', '/includes/js/media.js');
		$this->adminlayout->set('js', '/includes/js/carousel.js');
		
		$this->adminlayout->set('js', '/includes/js/jquery.imgareaselect-0.9.8/scripts/jquery.imgareaselect.min.js');
		$this->adminlayout->set('css', '/includes/js/jquery.imgareaselect-0.9.8/css/imgareaselect-default.css');
	
		$data['franchises']=$this->franchisemodel->getFranchisesByCountry(ACTIVE_COUNTRY, true);
		$data['venues']=$this->venues->get_list_of_records_dropdown();
		
		$data['categories']=$this->commonlib->categories_id;
		unset ($data['categories'][4]);
		unset ($data['categories'][5]);
		unset ($data['categories'][6]);
	
		$data['promo_options']=array (
			'PUBLIC' => 'Public event',
			'CHOICE' => 'Choice event',
			'CHOICE_FP' => 'Choice event displayed in a front page',
		);
	
		$data['paid']=array(
			'true' => 'Paid listing',
			'false' => 'Unpaid listing'
		);
		
		$data['mailout']=array(
			'true' => 'Include into the newsletter',
			'false' => 'Not to be listed in a newsletter'
		);
	
		$this->adminlayout->set('body', $data, $view);
		$this->adminlayout->loadAdmin();
	}

  } // END CLASS EVENTS CONTROLLER
?>