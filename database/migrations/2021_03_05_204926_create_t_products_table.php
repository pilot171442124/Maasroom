<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('ProductId');            
            $table->integer('ProdCatId')->length(10)->unsigned();
            $table->string('ProductName',50)->unique();
            $table->float('Price',12,2)->default(0);
            $table->string('ImageURL',150)->nullable();
            $table->string('Remarks',350)->nullable();
            $table->integer('Availability')->length(10);
            $table->timestamps();
            $table->foreign('ProdCatId')->references('ProdCatId')->on('t_product_category');
        });

        /*Default value insert*/
        DB::table('t_products')->insert([
            ['ProductId' => '1','ProdCatId' => 1,'ProductName' => 'Oyster Mushroom','Price' => 1350,"ImageURL"=>"products/p1.jpeg","Remarks"=>"The oyster mushrooms have three distinct parts- a fleshy shell or spatula shaped cap (pileus) , a short or long lateral or central stalk called stipe and long ridges and furrows underneath the pileus called gills or lamellae.",'Availability' => 40],
            ['ProductId' => '2','ProdCatId' => 1,'ProductName' => 'Button Mushroom','Price' => 1450,"ImageURL"=>"products/p2.jpeg","Remarks"=>"The mushroom contains an especially high amount of vitamin B and potassium.",'Availability' => 25],
            ['ProductId' => '3','ProdCatId' => 1,'ProductName' => 'Paddy Straw Mushroom','Price' => 1500,"ImageURL"=>"products/p3.jpeg","Remarks"=>"The Paddy Straw Mushroom is a species of edible mushroom cultivated throughout East and Southeast Asia and used extensively in Asian cuisines.",'Availability' => 20],
            ['ProductId' => '4','ProdCatId' => 1,'ProductName' => 'Wood Ear mushroom','Price' => 1000,"ImageURL"=>"products/p4.jpeg","Remarks"=>"The Wood Ear mushrooms, botanically classified as Auricularia polytricha, are a wild, edible ear jelly fungus that is a member of the Auriculariaceae family.",'Availability' => 30]
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_products');
    }
}
