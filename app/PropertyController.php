<?php
class Apptha_Airbnbclone_PropertyController extends Mage_Core_Controller_Front_Action
{
                public function indexAction()
                {
                        $this->loadLayout();
                        $this->renderLayout();
                }
                public function postAction(){
                 
                    $this->loadLayout();
                    $this->renderLayout();
                    $customer = Mage::getSingleton('customer/session')->getCustomer();
                    $CusId = $customer->getId();
                    $CusEmail = $customer->getEmail();
                    $post = $this->getRequest()->getPost();

                    
                    $amenity = array();
                    $amenity = implode(",", $post['amenity']);
                    $amenity = str_replace(" ", "", $amenity);
                    $random =  rand(1, 100000000000);
                    $sku = rand (1, $random);


                    if ($post) {

                            //$product = Mage::getModel('catalog/product');
                             $product = new Mage_Catalog_Model_Product();
                             $store_id = Mage::app()->getStore()->getId();
                            // Build the product
                            $product->setStoreID($store_id)
                                    ->setStatus(2);
                            $product->setTotalrooms($post['room']);
                            $product->setSku($sku);
                            $product->setUserid($CusId);
                            $product->setAttributeSetId(4);
                            $product->setTypeId('property');
                            $product->setName(htmlentities( $post['name']) ) ;
                            //$product->setCategoryIds(array(1)); # some cat id's, my is 7
                            $product->setWebsiteIDs(array(1)); # Website id, my is 1 (default frontend)
                            $product->setDescription($post['desc']);
                            $product->setShortDescription($post['sdesc']);
                            $product->setPrice($post['price']); # Set some price
                            //$product->addImageToMediaGallery(Mage::getBaseDir('media') . DS . 'import' . trim("/a/_/a.png"), NULL, false, false);
                            # Custom created and assigned attributes
                            $product->setAccomodates($post['accomodate']);
                            $product->setHostemail($CusEmail);
                            $product->setpropertyadd($post['address']);
                            $product->setAmenity($amenity);
                            $product->setState($post['state']);
                            $product->setCity($post['city']);
                            $product->setCountry($post['propcountry']);
                            $product->setCancelpolicy($post['cancelpolicy']);
                            $product->setPets($post['pets']);
                            $product->setBedtype($post['bedtype']);
                            $product->setMaplocation($post['map']);
                            $product->setPropertytype(array($post['proptype']));
                            $product->setPrivacy(array($post['privacy']));
                            $product->setCategoryIds(Mage::app()->getStore()->getRootCategoryId());
                            //$product->setType('my_custom_attribute4_val');

                            //Default Magento attribute
                            //$product->setWeight(4.0000);

                            $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
                            $product->setStatus(1);
                            $product->setTaxClassId(0); # My default tax class
                            $product->setStockData(array(
                                'is_in_stock' => 1,
                                'qty' => 100000
                            ));

                        $product->setCreatedAt(strtotime('now'));
                        //Mage::getModel('airbnbclone/airbnbclone')->imageupload($_FILES);
                        $product->save();
                        
                        Mage::getSingleton('core/session')->addSuccess($this->__('Space created Successfully'));
                        //return $this->_redirectUrl(Mage::helper('airbnbclone')->getshowlisturl());

                        $url = Mage::getBaseUrl()."airbnbclone/property/image/id/".$product->getId();
                        Mage::app()->getFrontController()->getResponse()->setRedirect($url);
                    } else {
                        Mage::getSingleton('core/session')->addError($this->__('Error'));
                        $this->_redirect('*/*/');
                    }

                }
                public function formAction()
                {
                        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::helper('airbnbclone')->getformUrl());
                        $this->loadLayout();
                        $this->_initLayoutMessages('catalog/session');
                        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                            Mage::getSingleton('core/session')->addError($this->__('Login and create your space'));
                            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                        }
                        else
                        {
                                $this->getLayout()->getBlock('head')->setTitle($this->__('List your Space'));
                                $this->renderLayout();
                        }
                }
                public function editAction()
                {


                        $this->loadLayout();
                        $this->_initLayoutMessages('catalog/session');
                        $customer = Mage::getSingleton('customer/session')->getCustomer();
                        $id = $customer->getId();
                        if($id)
                        {
                                $this->getLayout()->getBlock('head')->setTitle($this->__('Edit your Place'));
                                $this->renderLayout();
                        }else{
                                Mage::getSingleton('core/session')->addError($this->__('You are not currently logged in'));
                                $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());

                        }
                }
                public function showAction()
                {
                    
                        $this->loadLayout();
                        $this->_initLayoutMessages('catalog/session');
                        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                                $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                        }
                        else{
                                $this->getLayout()->getBlock('head')->setTitle($this->__('My List'));
                        }
			$this->renderLayout();
		}
		public function imageAction()
		{
                    $this->loadLayout();
                    $this->_initLayoutMessages('catalog/session');
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
			$this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                    }
                    else{
                		$this->getLayout()->getBlock('head')->setTitle($this->__('My List'));
                    }	
                    $this->renderLayout();
		}
		public function imageuploadAction()
		{
                        $this->loadLayout();
                        $entity_id= $this->getRequest()->getParam('id');
			Mage::getModel('airbnbclone/airbnbclone')->imageupload($_FILES,$entity_id); 
			$this->renderLayout();
			return;
		}
		public function updateAction()
		{
                        $post = $this->getRequest()->getPost();
                         $this->loadLayout();
                         $this->_initLayoutMessages('catalog/session');
                         $this->getLayout()->getBlock('head')->setTitle($this->__('Airbnbclone'));
                         $this->renderLayout();
                         Mage::getModel('airbnbclone/airbnbclone')->updateproperty($post);
                         Mage::getSingleton('core/session')->addSuccess($this->__('Your place updated successfully'));

                         
                         $url = Mage::getBaseUrl()."airbnbclone/property/image/id/".$post["id"];
                         Mage::app()->getFrontController()->getResponse()->setRedirect($url);
		     //return $this->_redirectUrl(Mage::helper('airbnbclone')->getshowlisturl());
		}
                public function viewAction()
		{
                        $this->loadLayout();
                        $this->_initLayoutMessages('catalog/session');
			$this->getLayout()->getBlock('head')->setTitle($this->__('View'));	
			$this->renderLayout();
		}
                public function yourtripAction()
		{
                    
                    $this->loadLayout();
                    $this->_initLayoutMessages('catalog/session');
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                    }
                    else{
                            $this->getLayout()->getBlock('head')->setTitle($this->__('Your Trip'));
                    }
                    $this->renderLayout();
		}
                public function searchAction()
		{
                    $this->loadLayout();
                    $this->_initLayoutMessages('catalog/session');
                    $this->getLayout()->getBlock('head')->setTitle($this->__('Search List'));
                    $this->renderLayout();
		}
                public function infoAction()
		{
                    $this->loadLayout();
                    $this->_initLayoutMessages('catalog/session');
                    $this->getLayout()->getBlock('head')->setTitle($this->__('Info'));
                    $this->renderLayout();
		}
                public function checkavailAction()
		{
		
		
			Mage::getModel('airbnbclone/airbnbclone')->checkavail(); 	
			
		}
                public function calenderAction()
                {
                    $productId = $_GET["productid"];
                    $selectedArray = Mage::getModel('airbnbclone/airbnbclone')->getdate($productId);
		

                        if(!isset($_GET["date"] )){
                            if(!isset($_GET["x"])){
                                    $x=date("n");
                            }
                        else{
                                $x=$_GET["x"];
                        }
                        if($x=="") $x = date("n");

                            $year = date("Y");
                            $date = strtotime("$year/$x/1");
                            $day = date("D",$date);
                            $m = date("F",$date);
                        }
                        else{
                            $dateSplit = explode("__",$_GET["date"]);
                            $x = $dateSplit[0];
                            if($x=="") $x = date("n");
                            $year = $dateSplit[1];
                            $date = strtotime("$year/$x/1");
                            $day = date("D",$date);
                            $m = date("F",$date);
                        }
                        $totaldays = date("t",$date); //get the total day of specified date


                        echo "<table border = '1' cellspacing = '0'  bordercolor='blue' cellpadding ='2' class='calend'>
                        <tr class='weekDays'>
                        <th><font size = '2' face = 'tahoma'>Sun</font></th>
                        <th><font size = '2' face = 'tahoma'>Mon</font></th>
                        <th><font size = '2' face = 'tahoma'>Tue</font></th>
                        <th><font size = '2' face = 'tahoma'>Wed</font></th>
                        <th><font size = '2' face = 'tahoma'>Thu</font></th>
                        <th><font size = '2' face = 'tahoma'>Fri</font></th>
                        <th><font size = '2' face = 'tahoma'>Sat</font></th>
                        </tr> ";

                        if($day=="Sun") $st=1;
                        if($day=="Mon") $st=2;
                        if($day=="Tue") $st=3;
                        if($day=="Wed") $st=4;
                        if($day=="Thu") $st=5;
                        if($day=="Fri") $st=6;
                        if($day=="Sat") $st=7;

                        if ($st >= 6 && $totaldays == 31) {
                        $tl=42;
                        }elseif($st == 7 && $totaldays == 30){
                        $tl = 42;
                        }else{
                        $tl = 35;
                        }

                $ctr = 1;
                $d=1;

        

                for($i=1;$i<=$tl;$i++){

                if($ctr==1) echo "<tr>";

                if($i >= $st && $d <= $totaldays){


                    if(strtotime("$year-$x-$d") < strtotime(date("Y-n-j")) ){
                            echo "<td align='center' class='previous days '><font size = '3' face = 'tahoma'>$d</font></td>";
                    }
                    else{
                            if(in_array("$year-$x-$d",$selectedArray) ){
                                    echo "<td  class='selected days' align='center'><font size = '3' face = 'tahoma'>$d</font></td>";
                            }
                            else{
                                    echo "<td class='normal days' align='center' ><font size = '3' face = 'tahoma'>$d</font></td>";
                            }
                    }
                    $d++;
                }
                else{
                        echo "<td>&nbsp</td>";
                }

                $ctr++;

                    if($ctr > 7) {
                        $ctr=1;
                        echo "</tr>";
                    }

                }

                echo '</table>';

		}
                public function statusAction(){
                    $status = $this->getRequest()->getParam('status');
                    $productId = Mage::app()->getRequest()->getParam('productid');
                    $status = Mage::getModel('airbnbclone/airbnbclone')->status($status,$productId);
                    if($status){
                        echo "Available";
                    }
                    else{
                        echo "NotAvailable";
                    }
                   
                }
                public function albumupdateAction()
		{
			 $post = $this->getRequest()->getPost();
                         $entityId = $this->getRequest()->getParam('entity_id');
                         $imageCollection = $this->getRequest()->getParam('imageCollection');
                         if($this->getRequest()->getParam('remove') =="1"){
                             for($i=0;$i<count($imageCollection);$i++){
                                 if($imageCollection[$i]){
                                    Mage::getModel('airbnbclone/airbnbclone')->removeImage($imageCollection[$i],$entityId);
                                 }
                             }
                             return $this->_redirectUrl( Mage::getBaseUrl().'airbnbclone/property/image/id/'.$entityId);
                         }                        
                        $this->loadLayout();
		
			Mage::getModel('airbnbclone/airbnbclone')->albumupdate($post); 
			$this->renderLayout();
			return $this->_redirectUrl(Mage::helper('airbnbclone')->getshowlisturl());
		}
                public function reviewAction(){
                        $this->loadLayout();
			$this->renderLayout();
                }
                public function reviewstatusAction(){
                    $status = $this->getRequest()->getParam('status');
                    $reviewid = Mage::app()->getRequest()->getParam('reviewid');
                    $status = Mage::getModel('airbnbclone/airbnbclone')->review($status,$reviewid);
                    if($status=="2"){
                        echo "Available";
                    }
                    else{
                        echo "NotAvailable";
                    }
                    
                }
                function reviewPageAction(){
                    $page = $this->getRequest()->getParam('page');                    
                    $productId = $this->getRequest()->getParam('product');

                    $reviews = Mage::getModel('review/review')->getResourceCollection();
                    $reviews->addStoreFilter(Mage::app()->getStore()->getId())
                                                ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                                                ->addEntityFilter('product', $productId)
                                                ->setDateOrder()
                                                ->addRateVotes()
                                                ->setPageSize(4)->setCurPage($page)
                                                ->load();
                    $reviews = $reviews->getData();
                    if (count($reviews)) {
                          for ($i = 0; $i < count($reviews); $i++) {
                                    $customerData = Mage::getModel('airbnbclone/airbnbclone')->getCustomerPictureById($reviews[$i]["customer_id"]);
                                        ?>
                                                    <div class="review-product">
                                                         <ul>
                                                            <li class="yourlist_img" style="float: left;">
                                                                <?php if($customerData[0]["imagename"]): ?>
                                                                <img src="<?php echo Mage::getBaseUrl('media')."catalog/customer/thumbs/".$customerData[0]["imagename"] ?>" style="width: 63px !important; height: 53px !important" alt="">
                                                                <?php else: ?>
                                                                <img src="<?php echo Mage::getBaseUrl('skin')."frontend/default/airbnb/images/" ?>" style="width: 63px !important; height: 53px !important" alt="">
                                                                <?php endif; ?>
                                                            </li>
                                                            <li style="float: left;width: 588px;padding-left: 10px;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
                                                                <div class="review-content ">
                                                                    <?php echo '<span style="font-weight:bold;font-size:14px;">"</span>' . nl2br($reviews[$i]["detail"]) . '<span style="font-weight:bold;font-size:14px;">"</span>'; ?>
                                                                    <div><?php echo '- ' . $reviews[$i]["nickname"] . ", " . date("dS, F Y", strtotime($reviews[$i]["created_at"]));?></div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                            <?php
                                                }
                                                //getting total count
                                                $reviewsTotal = Mage::getModel('review/review')->getResourceCollection();
                                                $reviewsTotal->addStoreFilter(Mage::app()->getStore()->getId())
                                                        ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                                                        ->addEntityFilter('product', $productId)
                                                        ->setDateOrder()
                                                        ->addRateVotes()
                                                        ->load();
                                                $totalRecords = count($reviewsTotal);
                                                 if($page>1):
                                                ?>
                                                <a href="javascript:void(0);" class='paginationClass' onclick="getPagination('1','<?php echo $productId ?>')" ><?php echo $this->__('First'); ?></a>
                                                <?php
                                                endif;
                                                for ($i = 1; $i <= ceil($totalRecords / 4); $i++) {
                                                    if($i ==$page)
                                                        echo "<a class='paginationClass'  href='javascript:void(0);'>".$i."</a>";
                                                    else
                                                        echo "<a class='paginationClass' href='javascript:void(0);' onclick='getPagination(\"$i\",\"$productId\")' >" . $i . "</a>";
                                                }
                                                if(ceil($totalRecords / 4) > $page):
                                                ?>
                                                <a href="javascript:void(0);" class="paginationClass" onclick="getPagination('<?php echo ceil($totalRecords / 4); ?>','<?php echo $productId ?>')"><?php echo $this->__('Last'); ?></a>
                                                <?php
                                                endif;
                                               
                                            } else {
                                                echo $this->__('There are no reviews yet for this product. Be the first to write a review..');
                                            }
                }
                function contactAction(){
                     $this->loadLayout();
                    $this->_initLayoutMessages('catalog/session');
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
						echo $this->__('You are not currently logged in');
                    }
                    else{
                        $this->loadLayout();
                        $this->renderLayout();
                    }
                }
                function saveinboxAction(){
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                    }
                    else{
                        $cid = $this->getRequest()->getParam('cid');
                        $pid = $this->getRequest()->getParam('pid');

                        $from = date("Y-m-d",strtotime( $this->getRequest()->getParam('from')) );
                        $to = date("Y-m-d",strtotime($this->getRequest()->getParam('to')) );
                        $no_of_guests = $this->getRequest()->getParam('number_of_guests');
                        $mailSubject = mysql_real_escape_string($this->getRequest()->getParam('mailSubject'));
                        
                        $hostCall = $this->getRequest()->getParam('hostCall');
                        $guest_preferences = $this->getRequest()->getParam('guest_preferences');
                        $data = array($cid,$pid,$from,$to,$no_of_guests,$mailSubject,$hostCall,$guest_preferences["time_zone"]);
                        $model = Mage::getModel('catalog/product');
                        $_product = $model->load($pid);

                        if(Mage::getModel('airbnbclone/airbnbclone')->saveInbox($data)){
                        	$senderName = Mage::getStoreConfig('trans_email/ident_support/name');
						    $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
						    $sender = array('name' => $senderName,
						                'email' => $senderEmail);	 
                 			$mailTemplate = Mage::getModel('core/email_template');   
							$template = 'received_messages'; 
							$mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>Mage::app()->getStore()->getId()))
							    ->sendTransactional(
							        $template,
							        $sender,
							        Mage::getModel('customer/customer')->load($cid)->getEmail(),
							        Mage::getModel('customer/customer')->load($cid)->getName(),
							        array(
							            'name' => Mage::getModel('customer/customer')->load($cid)->getName(),
							            'url' => Mage::app()->getStore()->getBaseUrl()
							        )
							    );   
                            Mage::getSingleton('core/session')->addSuccess($this->__('Mail sent successfully'));
                        }
                        else{
                            Mage::getSingleton('core/session')->addSuccess($this->__('Mail sending failed'));
                        }
                        return $this->_redirectUrl(Mage::getBaseUrl().$_product->getUrlPath());
                    }
                }
                function inboxAction(){
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                    }
                    else{
                	$messageid = $this->getRequest()->getParam('messageid');
                	if($messageid){
	                	if(Mage::getModel('airbnbclone/airbnbclone')->delelteMessage($messageid,"in")){
	                        Mage::getSingleton('core/session')->addSuccess($this->__('Deleted successfully'));
	                    }
	                    else{
	                        Mage::getSingleton('core/session')->addSuccess($this->__('Deletion failed. Try again..'));
	                    }
                	}
                        $this->loadLayout();
                        $this->renderLayout();
                    }
                }
                function senditemAction(){                   
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                    }
                    else{
                	$messageid = $this->getRequest()->getParam('messageid');
                	if($messageid){
	                	if(Mage::getModel('airbnbclone/airbnbclone')->delelteMessage($messageid,"out")){
	                        Mage::getSingleton('core/session')->addSuccess($this->__('Deleted successfully'));
	                    }
	                    else{
	                        Mage::getSingleton('core/session')->addSuccess($this->__('Deletion failed. Try again'));
	                    }
                	}
                        $this->loadLayout();
                        $this->renderLayout();
                    }
                }
                function showmessageAction(){
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                    }
                    else{
                        $this->loadLayout();
                        $this->renderLayout();
                    }
                }
                function uploadphotoAction(){
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                    }
                    else{
                        if(isset($_FILES['profilePhoto']['name'])){
                            Mage::getModel('airbnbclone/airbnbclone')->updateProfilePicture();
                            $url = Mage::getBaseUrl()."airbnbclone/property/uploadphoto";
                            $this->_redirectUrl($url);
                        }
                        $this->loadLayout();
                        $this->renderLayout();
                    }
                }
                function replyAction(){
                    if (!Mage::getSingleton('customer/session')->isLoggedIn()) {  // if not logged in
                            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
                    }
                    else{
                        $messageid = $this->getRequest()->getParam('message_id');
                        $customerid = $this->getRequest()->getParam('customer_id');
                        $message = $this->getRequest()->getParam('message');
                        if(Mage::getModel('airbnbclone/airbnbclone')->replyMail($messageid,$customerid,$message)){
                            Mage::getSingleton('core/session')->addSuccess($this->__('Mail sent successfully')."..");
                        }
                        else{
                            Mage::getSingleton('core/session')->addError($this->__('Mail sent failed')."..");
                        }
                    }
                    $url = Mage::getBaseUrl()."airbnbclone/property/inbox/";
                    Mage::app()->getFrontController()->getResponse()->setRedirect($url);
                }
                function advsearchAction(){
                    $this->loadLayout();
                    $this->renderLayout();
                }
                function searchresultAction(){                   
                    $this->loadLayout();
                    $this->renderLayout();
                }
}
