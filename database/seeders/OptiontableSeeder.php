<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Option;
class OptiontableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $options = array(
  array('id' => '1','key' => 'primary_data','value' => '{"logo":"\\/uploads\\/core\\/logo.png","favicon":"uploads\\/favicon.png","contact_email":"contact@email.com","contact_phone":"911234567890","address":"Somewhere, India","socials":{"facebook":"https:\\/\\/www.facebook.com\\/","youtube":"https:\\/\\/youtube.com\\/","twitter":"https:\\/\\/twitter.com\\/","instagram":"https:\\/\\/www.instagram.com\\/","linkedin":"https:\\/\\/linkedin.com\\/"},"footer_logo":"\\/uploads\\/core\\/logo-white.png"}','lang' => 'en'),
  array('id' => '2','key' => 'tax','value' => '0','lang' => 'en'),
  array('id' => '3','key' => 'base_currency','value' => '{"name":"USD","icon":"$","position":"left"}','lang' => 'en'),
  array('id' => '4','key' => 'invoice_data','value' => '{"company_name":"Ionfirm","address":"somewhere","city":"SM","country":"India","post_code":"123456"}','lang' => 'en'),
  array('id' => '5','key' => 'languages','value' => '{"en":"English"}','lang' => 'en'),
  array('id' => '6','key' => 'seo_home','value' => '{"site_name":"Home","matatag":"","matadescription":"","twitter_site_title":"home","preview":""}','lang' => 'en'),
  array('id' => '7','key' => 'seo_blog','value' => '{"site_name":"Blogs","matatag":"","matadescription":"","preview":""}','lang' => 'en'),
  array('id' => '8','key' => 'seo_about','value' => '{"site_name":"About Us","matatag":"","matadescription":"","preview":""}','lang' => 'en'),
  array('id' => '9','key' => 'seo_pricing','value' => '{"site_name":"Pricing","matatag":"","matadescription":"","preview":""}','lang' => 'en'),
  array('id' => '10','key' => 'seo_contact','value' => '{"site_name":"Contact Us","matatag":"","matadescription":"","preview":""}','lang' => 'en'),
  array('id' => '11','key' => 'seo_faq','value' => '{"site_name":"Faq","matatag":"","matadescription":"","preview":""}','lang' => 'en'),
  array('id' => '12','key' => 'seo_team','value' => '{"site_name":"Our Team","matatag":"","matadescription":"","preview":""}','lang' => 'en'),
  array('id' => '13','key' => 'seo_features','value' => '{"site_name":"Features","matatag":"","matadescription":"","preview":""}','lang' => 'en'),
  array('id' => '14','key' => 'seo_how_its_works','value' => '{"site_name":"How its work","matatag":"","matadescription":"","preview":""}','lang' => 'en'),
  array('id' => '15','key' => 'contact-page', 'value' => '{"address":"Somewhere, India,","country":"IN 123456,IN","map_link":"https:\/\/www.google.com.bd\/maps\/@24,85,15z","contact1":"911234567890","contact2":"911234567890","email1":"support@email.com","email2":"contact@email.com"}','lang' => 'en'),
  array('id' => '16','key' => 'banner', 'value' => '{"phone_image_1":"\\/uploads\/core\/banner_one.png","phone_image_2":"\\/uploads\/core\/banner_two.jpg","phone_image_3":"\\/uploads\/core\/banner_three.jpg","banner_header":"Revolutionize Your Marketing with WhatsCloud","usedthis":"10k+ Used This Platform","btnfirst":"Explore","btnsecond":"Sign In"}','lang' => 'en'),
  array('id' => '17','key' => 'features', 'value' => '{"feature_image":"\\/uploads\/core\/features_header.png","feature_header":"Features that makes platform different!","feature_subheader":"Discover the Array of Distinctive Features Setting Our Platform Apart","feature_1":"Secure API","feature_1_details":"Ensure Ironclad Security with our Reliable and Encrypted API Connection for Peace of Mind.","feature_2":"Template Messaging","feature_2_details":"Effortlessly Create and Send Customizable Template Messages for Engaging and Consistent Communication.","feature_3":"Auto Responder","feature_3_details":"Deliver Instant Replies and Streamline Communication with Automated Responses for Enhanced Customer Engagement.","feature_4":"24-7 Availablity","feature_4_details":"Experience Uninterrupted Access Anytime, Anywhere with our 24\/7 Availability for Seamless webhook Communication."}', 'lang' => 'en'),
  array('id' => '18','key' => 'about_section', 'value' => '{"frame_image":"\\/uploads\/2023\/07\/1689930896RqN0uj9bXBeV24CJNcKV.png","frame_image_2":"\\/uploads\/core\/about_two.png","about_header":"Empowering Connections, Transforming Experiences","about_subheader":"WhatsCloud is a revolutionary platform that empowers connections and transforms experiences. With its cutting-edge features and seamless integration, WhatsCloud revolutionizes communication, enabling businesses to reach new heights","feature_image_2":null,"about_api":"1200","satisfied_user":"937","customer_review":"772","about_countries":"63"}', 'lang' => 'en'),
  array('id' => '19','key' => 'overview', 'value' => '{"overview_image_1":"\\/uploads\/core\/overview_one.png","overview_image_2":"\\/uploads\/core\/overview_two.png","overview_image_3":"\\/uploads\/core\/overview_three.png","overview_header":"Embrace the Future with WhatsCloud","overview_subheader":"Welcome to WhatsCloud, the innovative platform designed to revolutionize communication. Seamlessly connect with your audience, streamline workflows, and unlock new opportunities for growth. Experience the power of advanced features, robust security, and unparalleled convenience. Embrace the future of communication with WhatsCloud.","overview_title_1":"Create & Send Templates","overview_subtitle_1":"Effortlessly create and send customizable templates for engaging and consistent communication with ease and efficiency.","overview_title_2":"Live Chat & Bulk Messaging","overview_subtitle_2":"Experience seamless live chat functionality and send bulk messages for efficient and effective communication with your audience.","overview_title_3":"Effortless Scheduling Experience","overview_subtitle_3":null}', 'lang' => 'en'),
  array('id' => '20','key' => 'work', 'value' => '{"step_image_1":"\\/uploads\/core\/step_one.jpg","step_image_2":"\\/uploads\/core\/step_two.jpg","step_image_3":"\\/uploads\/core\/step_three.jpg","video_image":"\\/uploads\/core\/video_thumbnail.jpeg","work_header":"How to Connect - 3 easy steps","work_subheader":"Effortlessly integrate your WhatsApp Cloud API with our platform in three simple steps and harness the potential of seamless communication.","step_title_1":"Set Up Your Facebook Developer Account","step_subtitle_1":"Create an App","step_description_1":"Initiate the process by creating an app on your Facebook Developer Account.","step_title_2":"Select Business as Your App Type","step_subtitle_2":"Provide Basic Business Information","step_description_2":"Enter essential details about your business to proceed with the integration.","step_title_3":"Access WhatsApp Integration","step_subtitle_3":"Accept Terms and Conditions","step_description_3":"Scroll down and click Continue to agree to WhatsApp Cloud APIs terms and conditions. Fill in your business information, connect your phone number, and start utilizing WhatsApp Cloud.","video_header":null,"video_url":"https:\/\/YouTube.com"}', 'lang' => 'en'),
  array('id' => '21','key' => 'download', 'value' => '{"download_header":"Get Unlimited Experience With Whatscloud","download_subheader":"Start it Today","hero_image_1":"\\/uploads\/core\/explore_one.png","hero_image_2":"\\/uploads\/core\/explore_two.png"}', 'lang' =>'en'),
);

    Option::insert($options);

  }
}
