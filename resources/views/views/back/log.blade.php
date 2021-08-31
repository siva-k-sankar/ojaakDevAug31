@extends('back.layouts.app')

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Change Log</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.14</br><small>25-01-2020 Demo</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Admin</strong></h5>
                        <ul>
                            <li>Admin can chat with a particular user from his dashboard.Admin chat messages are also e-mailed to that particular user.</li>
                            <li>Admin can view the followers and following on any user by clicking their followers/following count.</li>
                            <li>Admin can view all the verified proofs of any user from users page in admin dashboard.</li>
                            <li>Admin can navigate to a particular user's page by clicking his username from followers/following list.</li>
                        </ul>
                        <h5><strong>User</strong></h5>
                        <ul>
                            <li>User can view followers/following by clicking the count and navigate to any user by clicking his username.</li>
                            <li>User can also follow or unfollow while viewing his followers and following.</li>
                            <li>Wallet id added for user,by clicking the passbook,particular user's transaction history is displayed.</li>
                            <li>Map is added for selecting a particular location by user while posting the ads.</li>
                            <li>Active ads status could be changed to inactive by the user.In the inactive ads section user could again edit and post the ad,then ads status is again changed to pending until admin approves the ad.</li>
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
        
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.13</br><small>11-01-2020 (live) & Demo</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-danger">bug fix</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Delete user functionality</li>
                            <li>Server error problem fixed</li>
                            <li>plans page toastr error fixed</li>
                            <li>inactive ads status fixed</li>
                        </ul>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Admin</strong></h5>
                        <ul>
                            <li>A faq function is added  in management in dashboard</li>
                            <li>View the reported users & ads in complaints in dashboard</li>
                            <li>setting points are changed to floating </li>
                            <li>view particular user's profile. admin can also view all ads  posted by the users. And follwers ,follwing , approved ads count are displayed</li>
                        </ul>
                        <h5><strong>User</strong></h5>
                        <ul>
                            <li>Ads list,Ads view,Profile page designed.</li>
                            <li>Approved time is displayed in the ads.</li>
                            <li>view page custom field data added,map is integrated.Related ads are displayed in the bottom.Report ads link is added to the page.View profile functionality is added.Mobile number is displayed if the user specify it while posting ad or else not displayed.</li>
                            <li>In the user profile page approved ads are displayed.Linked accounts are displayed.Followers and following counts are displayed.Share profile link is displayed.Report user option is displayed.</li>
                            <li>Follow unfollow option is displayed if the particular user and logged in user are different.If both the user are same edit profile option is displayed.</li>
                            <li>Ajax login designed.Contact form with attachment mail and FAQ page is added.</li>
                            <li>Free ads and paid ads are categorized under active ads.</li>
                            <li>Sold option is enabled only to approved ads.</li>
                            <li>If the product in a ad is sold then the ad is moved under sold items category.</li>
                            <li>Rejected ads are categorized under inactive ads.</li>
                        </ul>
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.12</br><small>28-12-2019 (live)</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Admin view particular user page</li>
                            <li>Admin block,unblock particular user</li>
                            <li>if the user is blocked then the reason is displayed</li>
                            <li>Approved ads count is displayed in user page</li>
                            <li>Admin settings 4 wallet added</li>
                            <li>admin can restrict  the user by  no of free ads view count per day</li>
                            <li>Ads view point and ads post point has been added to the admin</li>
                            <li>Home page design</li>
                            <li>sold ads list in users ads page</li>
                            <li>Favourites ads list in users ads page</li>
                            <li>Follow and unfollow a particular user</li>
                            <li>view particular user</li>
                        </ul>
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.11</br><small>21-12-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Admin login bug fixed</li>
                            <li>Admin edit all ads</li>
                            <li>Admin reject ads with reason</li>
                            <li>user ads restricted exceed limits move to the plan page</li>
                            <li>plan purchase to the added user purchase limits</li>
                            <li>admin users  module display user details</li>
                            <li>serverside data rectrive for data loaded fast some module(user,ads verification,plans,plan history,location request)</li>
                            <li>for security reason for change uuid process on proof verification in admin and user side</li>
                        </ul>
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.10</br><small>14-12-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Edit ads data and associate custom field data</li>
                            <li>Ads moved to the approval of admin</li>
                            <li>Admin can view all ads</li>
                            <li>Admin have a permission to view particular ad </li>
                            <li>Admin approves the ads</li>
                        </ul>
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.9</br><small>10-12-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>create custom fields  & options</li>
                            <li>custom fields map to the categories</li>
                            <li>Loaded custom fields based on categories</li>
                            <li>stored the custom fields values and ads data.</li>
                        </ul>
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.8</br><small>28-11-2019 (live)   &    02-12-2019 (video) </small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-danger">Bugfixed</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li> Ad posting Category selection to be changed to tree view.</li>
                            <li> Based on category selection hash tags to be loaded automatically. (If user no need that tag, he can remove or add new one).</li>
                            <li>For auto hash tag loading, hash tag field to be added in Admin category creation form.</li>
                            <li>Show / hide buttons to be toggle switch.</li>
                            <li>"Cities requested" should not displayed to users.</li>
                            <li>Referral code, "OJAAK-" to be displayed default.</li>
                            <li>during mobile or Email verification, user should not skip the process by clicking the Close (X) symbol.</li>
                        </ul>
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.7</br><small>16-11-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Drag and drop image feature is added in the add posting page,in which multiple image could be selected and if not wanted,it also could be removed from the image preview.</li>
                            <li>In the user ad edit,when updating the image,unwated image is deleted to optimize the storage,which in turn deletes the image from the DB and the image directory.</li>
                            <li>The city selectiton has option "Not in the list" has been bug fixed from the previous demo.If an user selects this option from the list,the request for approving it is processed to the admin.</li>
                            <li>The user has been limited with one free ad for posting.When the limit of the a exceeds,it is redirected to the plans page.</li>
                            <li>User has been given privelages to view and edit the already existing ad.In the prvious demo,only ad upload was added.Now as a new feature for the user,viewing and editting ad hasbeen added.</li>
                            <li>User is notified of the active ads and in active ads.All the current inactive ads are displayed to the user as a seperate functoionality.</li>
                            <li>In the privious demo,only the request from the user could be viewed by the admin but no actions were added.Whereas in this demo a new action functionality for approving the request from the user has been added.If the user specifies an already existing city,admin could update the id of the city to the particular post.If the city does'nt exist then city is created in the list and id is updated.</li>
                            <li>Plan module has been created,which contains add button on clicking it,redirects to a page for adding new plan.</li>
                            <li>In the plan module,status could be updated or the existing plan could be deleted.</li>
                            
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.6</br><small>09-11-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-danger">Bugfixed</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Registration "Name" to be removed</li>
                            <li>"Name" to be changed to "Display name"</li>
                            <li>For Id proof verification mail to be trigered to customer</li>
                            <li>For ID proof verification date and time management to be added</li>
                            <li>For ID proof approval, Add "OTHERS" in the drop down menu... if "others" clicked.. discription box to be opened. and this discription to be sent to customer</li>
                            <li>Add tree view for Categories</li>
                            <li>Give examples for TAGs inside the text box during Ad Post form filling</li>
                            <li>Water mark to be added to the ad posted photos</li>
                            <li> Photos quality to be reduced to save DATABASE space</li>
                            <li>In post ad form. change name from " Seller information" to "Your information" or "Contact details"</li>
                            <li> Add button "show / dont show" for Contact details to other customers in Seller information</li>
                            <li>"Page Under construction" to be displayed in "www.ojaak.com"</li>
                        </ul>
                    </div>
                  <!-- /.box-body -->
                  <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Add countries button is added in the location page.When new category is added,it can be viewed,edited,deleted and status can be changed.</li>
                            <li>State could be created for an existing Countries.All the similar functions to countries could be created.</li>
                            <li>Cities could be created for an existing State.All the similar functions to State could be created.</li>
                            <li>Active cities are displayed in the Select 2 text box.</li>
                            <li>Ck editors Fixed </li>
                            <li>Added location requested Page</li>
                            <li>admin can view the location requested by the customer</li>
                            <li>random user name is generated</li>
                            <li>random profile picture is added as default profile picture</li>
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.5</br><small>31-10-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-danger">Bug Fixed</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Notification rename show/hide to enable/disable</li>
                            <li>Get reason from user for deactivate</li>
                            <li>have ref code link and click link show text box to enter ref code</li>
                            <li>otp generation for mobile number in the registeration.</li>
                            <li>If user signup with email ask phone no in home page</li>
                            <li>If user signup with Phone ask email in home page</li>
                            <li>option to have Delete the govt proof for user</li>
                            <li>Admin after approve govt proof edit option for user then change the image name,.. not status show be not verified</li>
                            <li>store admin details who have approved govt id proof,etc,....other activities</li>
                            <li>Admin Govt proof reject with commends status show to user.Not to delete the govt proof</li>
                            
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>General.
                                <ul>
                                    <li>Background setup for the Table  on the State,cities and country</li>
                                    <li>Background setup for the Table  on the Categories and Sub-Categories</li>
                                    <li>Background setup for the Table  on the ADscontrol</li>
                                </ul>
                            </li>
                            <li>Admin
                                <ul>
                                    <li>differentiate the verified proofs and un-verified proofs for displayed and their functionality </li>
                                    <li>Add category button is added in the category page.When new category is added,it can be viewed,edited,deleted and status can be changed.</li>
                                    <li>Sub category could be created for an existing category.All the similar functions to category could be created.</li>
                                    
                                </ul>
                            </li>
                            <li>Users
                                <ul>
                                    <li>In the ad posting page,the seller inormation is displayed if logged in else not shown.</li>
                                    <li>Category could be selected from dropdown.If it is selected,the sub categories are displayed.</li>
                                    <li>Active cities are displayed in the auto complete text box.</li>
                                    <li>Tags could be created by the user.</li>
                                    <li>Multiple images could be chosen by the user and preview of the image are displayed.</li>
                                    <li>If the user is registered,ads could be posted instantly,else the user is asked to register before posting the ad.</li>
                                    
                                </ul>
                            </li>
                            
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.4</br><small>09-10-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-danger">Bug Fixed</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>User age limit has been set to 18 years.No one below 18 years could register.</li>
                            <li>Default user image has been set.</li>
                            <li>Work mail should not be similar to the previously existing mail.</li>
                            <li>Fixed the bugs in the mail free point.</li>
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                  <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Govt Proof.
                                <ul>
                                    <li>Added different government proofs for the verification of the user.</li>
                                    <li>The proofs attached for the selection are aadhar,voter id,PAN card,Driving License.</li>
                                    <li>User can upload the image for the proof.</li>
                                    <li>Once the user uploads the proofs,a list of uploaded proofs are displayed for viewing by the customer.</li>
                                    <li>Until the admin verifies,the status for the user is not verified.</li>
                                    <li>Admin can view the unverified proofs uploaded by the customer.</li>
                                    <li>Either he can verify or delete the proofs.</li>
                                    <li>Once the admin verifies the proof,points are added.</li>
                                </ul>
                            </li>
                            <li>Settings for User
                                <ul>
                                    <li>Settings has privacy settings,manage blocked user,notification and deactivate account.</li>
                                    <li>Privacy settings has phone number,mail id,online availability and profile view chat.</li>
                                    <li>Notifications has recommendations and special communications.</li>
                                    <li>Manage blocked users has a list of users.If we select an user to block,after verification the user is blocked and his details are displayed.</li>
                                    <li>When the deactivate is clicked after confirmation account is deactivcated.</li>
                                    <li>After deactivating,if the user trys to login error message is displayed to contact the admin.</li>
                                </ul>
                            </li>
                            <li>Referral
                                <ul>
                                    <li>The referral code for existing user is displayed in the user profile dashboard.</li>
                                    <li>When new user registers,he can use the referral code from existing user or can register throgh referal link,in which the referal code is displayed automatically.</li>
                                    <li>But the referal code for new registration is not mandatory.</li>
                                    <li>If new user registers using the referral code,points are added to the user to whom the referral code belongs.</li>
                                </ul>
                            </li>
                            
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.3</br><small>05-10-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-danger">Bug Fixed</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>In admin panel Settings and points menu are designed as seperate menu.</li>
                            <li>In users profile page active menu are highlighted.</li>
                            <li>All the referal codes are updated with ojaak.</li>
                            <li>When email id, phone number and work email id is updated the previous id is displayed below.</li>
                            <li>Set image preview for profile picture change . </li>
                            <li>Add edit button functionality in user profile page to edit profile details</li>
                            
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                  <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Separate verification page is designed  to which page will get redirected when verification link send to email is clicked</li>
                            <li>Option for free point Adding</li>
                            <li>Add verified icon for verified user & their functionality</li>
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.2</br><small>27-09-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Admin template integrated</li>
                            <li>Admin profile</li>
                            <li>Admin Settings(points,site configuration,....)</li>
                            <li>Admin Change Password</li>
                            <li>Customer Update Mail</li>
                            <li>Customer Update Work Mail</li>
                            <li>Customer Update mobile</li>
                            <li>Customer Update Profile</li>
                            <li>Customer Update Google id</li>
                            <li>Customer Update Facebook id</li>
                            <li>Generate Referral Code And Show in Customer Profile View Page</li>
                        </ul>
                        
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
            <div class="col-lg-12 col-xs-12">
              <!-- small box -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">v1.0.1 </br><small>21-09-2019</small></h3>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title btn btn-success">Added</h3>
                    </div>
                    <div class="box-body">
                        <h5><strong>Web</strong></h5>
                        <ul>
                            <li>Option for new user registration</li>
                            <li>Option for login with facebook</li>
                            <li>Option for login with google account</li>
                            <li>Option for existing user login</li>
                            <li>Email or Mobile number can be used for login</li>
                            <li>For reset username and password mail</li>
                            <li>Option for User Logout</li>
                        </ul>
                        <h5><strong>Api</strong></h5>
                        <ul>
                            <li>Api for Registeration</li>
                            <li>Api for Facebook Login/Registeration</li>
                            <li>Api for Google Login/Registeration Api</li>
                            <li>Api for User Logout</li>
                            <li>Api for Otp Send </li>
                            <li>Api for Otp verification</li>
                            <li>Api for User Info</li>
                        </ul>
                    </div>
                  <!-- /.box-body -->
                </div>  
            </div>
        </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
    

@endsection