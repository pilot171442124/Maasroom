<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_blog', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('BlogId');            
            $table->string('BlogType',20);/*Text/Video/Racipies/News*/
            $table->datetime('BlogDateTime');
            $table->string('BlogTitle',50);
            $table->text('Thumbnail');
            $table->text('Content');
            $table->timestamps();
        });

         /*Default value insert*/
        DB::table('t_blog')->insert([
            ['BlogId' => '1','BlogType' => 'Text','BlogDateTime' => '2021-04-15 10:15:10','BlogTitle' => 'Mushrooms can help your heart health.',"Thumbnail"=>"blog/blog1.jpeg","Content"=>"The oyster mushrooms have three distinct parts- a fleshy shell or spatula shaped cap (pileus) , a short or long lateral or central stalk called stipe and long ridges and furrows underneath the pileus called gills or lamellae."],
            ['BlogId' => '2','BlogType' => 'Video','BlogDateTime' => '2021-04-20 21:30:32','BlogTitle' => 'শারীরিক প্রতিবন্ধকতাকে জয় করে মাশরুম বিপ্লব',"Thumbnail"=>'<iframe width="560" height="315" src="https://www.youtube.com/embed/mkSgmSLrwYw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',"Content"=>"The mushroom contains an especially high amount of vitamin B and potassium."],
            ['BlogId' => '3','BlogType' => 'Text','BlogDateTime' => '2021-05-02 15:14:33','BlogTitle' => 'Mushrooms may help keep you young',"Thumbnail"=>"blog/blog2.jpeg","Content"=>"The Paddy Straw Mushroom is a species of edible mushroom cultivated throughout East and Southeast Asia and used extensively in Asian cuisines."],
            ['BlogId' => '4','BlogType' => 'Text','BlogDateTime' => '2021-05-24 18:25:11','BlogTitle' => 'Health Benefits of Mushrooms',"Thumbnail"=>"blog/blog3.jpeg","Content"=>"The Wood Ear mushrooms, botanically classified as Auricularia polytricha, are a wild, edible ear jelly fungus that is a member of the Auriculariaceae family."]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_blog');
    }
}
