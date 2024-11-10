<?php
/*
    PHP image galleries - auto version - PHP > 5.0
*/

define('IMGDIR', 'photos');

define('WEBIMGDIR', 'photos');
// set session name for galleries "cookie"
define('SS_SESSNAME', 'galleries_sess');

$err = '';

session_name(SS_SESSNAME);
session_start();
// init galleries class
$ss = new galleries($err);
if (($err = $ss->init()) != '')
{
	header('HTTP/1.1 500 Internal Server Error');
	echo $err;
	exit();
}

$ss->get_images();

list($is_active, $img_cpt_name, $first, $prev, $next, $last, $random) = $ss->run();
/*
    galleries class, can be used stand-alone
*/
class galleries
{
    private $gallerise_array = NULL;
    private $err = NULL;

    public function __construct(&$err)
    {
        $this->gallerise_array = array();
        $this->err = $err;
    }
    public function init()
    {
        // run actions only if img array session var is empty
        // check if image directory exists
        if (!$this->dir_exists())
        {
            return 'Error retrieving images, missing directory';
        }
        return '';
    }
    public function get_images()
    {
        if (isset($_SESSION['imgarr']))
        {
                $this->gallerise_array = $_SESSION['imgarr'];
        }
        else
        {
            if ($dh = opendir(IMGDIR))
            {
                while (false !== ($file = readdir($dh)))
                {
                    if (preg_match('/^.*\.(jpg|jpeg|gif|png)$/i', $file))
                    {
                        $this->gallerise_array[] = $file;
                    }
                }
                closedir($dh);
            }
            $_SESSION['imgarr'] = $this->gallerise_array;
        }
    }
    public function run()
    {
        $is_active = rand(1,count($this->gallerise_array));
        $last = count($this->gallerise_array);
        if (isset($_GET['img']))
        {
            if (preg_match('/^[0-9]+$/', $_GET['img'])) $is_active = (int)  $_GET['img'];
            if ($is_active <= 0 || $is_active > $last) $is_active = 1;
        }
        if ($is_active <= 1)
        {
            $prev = $is_active;
            $next = $is_active + 1;
        }
        else if ($is_active >= $last)
        {
            $prev = $last - 1;
            $next = $last;
        }
        else
        {
            $prev = $is_active - 1;
            $next = $is_active + 1;
        }
        // line below sets the img_cpt_name name...
        $img_cpt_name = str_replace('-', ' ', $this->gallerise_array[$is_active - 1]);
        $img_cpt_name = str_replace('_', ' ', $img_cpt_name);
        $img_cpt_name = preg_replace('/\.(jpe?g|gif|png)$/i', '', $img_cpt_name);
        $img_cpt_name = ucfirst($img_cpt_name);
        return array($this->gallerise_array[$is_active - 1], $img_cpt_name, 1, $prev, $next, $last);
    }
    private function dir_exists()
    {
        return file_exists(IMGDIR);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ukraine Humanitarian Relief Fund</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            font: 100% Verdana, Arial, Helvetica, sans-serif;
            font-size: 14px;
        }
        p { 
            margin: 0; 
        }
	.tamilrokers {
            border: 1px #ccc solid;
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            text-align: center;
        }
        .tamilrokers .tamilrokers-nav {
            margin-bottom: 40px;
        }
        
        .tamilrokers .tamilrokers-nav a:first-child {
            margin-right: 10px;
        }
        .tamilrokers .tamilrokers-nav a:last-child {
            margin-left: 10px;
        }
        .tamilrokers .tamilrokers-image img {
            max-width: 100%;
            height: auto;
        }
        .tamilrokers .tamilrokers-image-label {
            color: #777;
        }
	a {
            color: #333;
        }
	a:hover {
            color: #cc0000;
        }
	.sp {
            padding-right: 40px;
        }
    </style>
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
    <div class="tamilrokers">
	
        <font size="6">
                <b>Humanitarian Relief for Ukraine</b>
        </font>
	
		<div class="tamilrokers-nav">
                <p> Cycle Photos:  
            <a href="?img=<?=$first;?>">First</a>
            <a href="?img=<?=$prev;?>">Previous</a>
            <span class="sp"></span>
            <a href="?img=<?=$next;?>">Next</a>
            <a href="?img=<?=$last;?>">Last</a>
            <a href="?img=<?=$random;?>">Random</a>
        </div>
        <div class="tamilrokers-image">
            <img src="<?=WEBIMGDIR;?>\\<?=$is_active;?>" alt="" />
        </div>
        <p class="tamilrokers-image-label"><?=$img_cpt_name;?></p>
		
		<form action="https://www.paypal.com/donate" method="post" target="_top">
		<input type="hidden" name="business" value="7HTPWXGJLSXMS" />
		<input type="hidden" name="no_recurring" value="0" />
		<input type="hidden" name="currency_code" value="USD" />
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
		</form>
		
                <p> This page is dedicated to my younger brother, Daniil, who tragically passed away on September 20, 2022. Love and Rest In Peace, Daniil.              
                <img style='border:1px solid #000000' src="Daniil-photo.jpg" />
                
                <p>	Dear friends, my name is Dmitro. 
                		
		<p> This page is created for my solo humanitarian relief organization. 
			Currently, all my volunteering work is funded by myself with my personal income and donations I've received from generous volunteers (shown below), 
			However, more funding can provide additional aid, which I cannot fund with my paycheck.
                <p> Update: Being a part of the Bay Area massive wave of layoffs, currently, and for the next short term, my volunteer work can only be funded through donations 
                        
		<p> This is my third trip by plane from USA to Ukraine and I am prepared to support Ukraine's citizens, families, and soldiers, until and beyond the end of this horrible invasion
						
		<p> Below is a record of what I have finished so far at my own expense and what I am looking to do with funds provided by donations.
			With my current income I will continue at this steady pace, but more can be accomplished with additional funding.
			
		<p> I will update this page every week and manually enter all donors as donations arrive. Any amount or sharing is appreciated.
			All donations will 100% go toward purchasing supplies, humanitarian aid, housing, or other donations. I can provide receipts and photos to anyone who needs. Thank you all
			
		<p> Completed Personally:			
			
		<p>* 350 hours volunteer time = $8750 in company match donations to several select Ukraine funds
		<p>* Ongoing financial support for multiple families = $3000(approx)
                
                
                <p> Updates for 2023
                        <p> Sent 1 DJI Mavic Drone
                        <p> Sent 1 Armalite nightvision mokocular
                        <p> Sent 4 AGM Thermal Monoculars
                        <p> Sent 5 FPV drones
                        <p> Sent 3 Pixhawk autopilot contollers
                        <p> Sponsored 4 Ukrainian refugees  abroad    
                                
                                
                
                <p> Update for December 2022:
<p class="m-0"> 13 luggage supply bags delivered to Warsaw aiport
<p class="m-0"> Purchased in USA, Poland, and donated supplies (using Bereavement time) ~ $6000:
<p class="m-0"> 1 car, 2002 Ford focus hatchback,
<p class="m-0"> 1 thermal monocular,
<p class="m-0"> 12 optics,
<p class="m-0"> 12 weaver kits,
<p class="m-0"> 12 flashlights,
<p class="m-0"> 6 ar500 armor vests,
<p class="m-0"> 15 zero deg sleeping bags,
<p class="m-0"> 20 power banks,
<p class="m-0"> 2 generators,
<p class="m-0"> 10 inverters,
<p class="m-0"> 2 car batteries,
<p class="m-0"> 3 pairs of mil boots,
<p class="m-0"> 10 galoshes,
<p class="m-0"> 3 backpacks,
<p class="m-0"> 2 laptops,
<p class="m-0"> 2 winter uniforms,
<p class="m-0"> 30 usb lights,
<p class="m-0"> 20 pair gloves,
<p class="m-0"> various medical supplies,
<p class="m-0"> 30 CAT7 and SOF tourniquets,
<p class="m-0"> 60 civillian med kits,
<p class="m-0"> 10 chest seals,
<p class="m-0"> drugs, antibiotics, painkillers, bandages, patches, etc,
<p> 100 kg dog food
<p> 

<p class="m-0"> Updated: September 2022
                
		<p class="m-0">* 20 class 3 armor vests with 40 AR500 6mm plates and custom coating 
                                
		<p class="m-0">* Item donations to Kharkiv bought and brought from USA = ($4000 total): 
		<p class="m-0"> * 20 NAR CAT7 tourniquet 
<p class="m-0"> 20x uniforms,                
<p class="m-0"> 20x gloves,
<p class="m-0"> 2x boots,
<p class="m-0"> 1x military backpack,
<p class="m-0"> 1x helmet,
<p class="m-0"> 60L fuel,
<p class="m-0"> 3x plate carrier,
<p class="m-0"> 4x pouch,
<p class="m-0"> 8x utility bag,
<p class="m-0"> 16x kneepads,
<p class="m-0"> 2x powerbanks,
<p class="m-0"> 1x solar panel,
<p class="m-0"> 15x folding shovels,
<p class="m-0"> 8x sleeping bags,
<p class="m-0"> 11x soldier medkits,
<p class="m-0"> 50x civilian med kits (hemostatic bandage, tourniquet, antibiotic, bandage),
<p class="m-0"> 2x ceramic Level 3 carrier plates,
<p class="m-0"> 2x ar500 steel level 3 carrier plates,
<p class="m-0"> 1x DJI Mavic2 drone,
<p class="m-0"> 1x Armalite nightvision optic,
<p class="m-0"> 1x AGM scout thermal optic ,
<p class="m-0"> 1x laptop,
<p class="m-0"> 3x 3x9x40 optic,
<p class="m-0"> 1x Holosun holographic sight,
<p class="m-0"> 8x weaver rail accessory
<p class="m-0">* Transportation of 20 bags for others from USA to Warsaw, Poland - 0.5 tonnes of supplies
<p>* Covering evacuation fees for family from occupied Kherson to Zaporizhia

<p>
                

                <p> Updated April 2022:

                <p class="m-0">* Manufacture, order, and shipment of 26 AR400 armor plates to Kharkiv

                <p class="m-0">* Purchase, packaging, and shipment of food, medicine, clothes, and supplies to soliders in Kharkiv 

                <p class="m-0">* Donation of gas masks, antibiotics, bags, torniquetes, quickclot, and sleeping bags to soldiers in Cherkassy
                
		<p class="m-0">* Transporation of people from Ukraine/Poland border to resettle within Poland = 35 persons so far
			to Krakow (3hr), Wroclaw(5hr), Poznan(8hr), Warsaw(5hr), Katowice(4hr)

		<p class="m-0">* Temporary housing for families = Rent for 1 month for family of 2 
			
		<p class="m-0">* Temporary housing for families = Large apartment with 3 rooms and 7 days stay in Krakow 
			
		<p class="m-0">* Purchase and delivery of bulk food and supplies to Ukraine = 4 deliveries, 4000lbs total
			
		<p class="m-0">* Direct monetary donations to various families
		
		<p class="m-0">* Aquired a car with registration to cross borders into Ukraine

		<p class="m-0">* Feeding abandoned pets in Kyiv, Lviv, and Irpin regions
		
		
		<p class="m-0">Completed with other foundations:
		
		<p class="m-0">* Driver to Cherkassy Oblast, transportation of 2 full cars of food and evacuation of 15 people
                
                <p class="m-0">* Driver and delivery of 4 transportation vans to Lviv to transport people from Kyiv 
			organized and funded by Every Nation Madrid <a href="www.vidapasionada.org">www.vidapasionada.org</a>
			
		<p class="m-0">* Retrofit and paint old school building to house 800 people, project by Patrycjusz Gaweł <a href="https://zrzutka.pl/unitatem">https://zrzutka.pl/unitatem</a>
						
			
			
		
		<p class="m-0">*. ...
                
		<p>note: Under Ukranian law photos of military and military activity are not allowed, unless provided by recepients with blurred faces
			
		<p> Thank you donors:
		
		<p class="m-0"> Tyler Bond +$100  March 17
		<p class="m-0"> Meng Lu +$50  Mar 17		
		<p class="m-0"> Donna Bond +$100  Mar 18 
		<p class="m-0"> Serafima Krikunova +$300  Mar 19
		<p class="m-0"> Kateryna Sadovnycha +$50  Mar 19
		<p class="m-0"> Nathanael Tjepkema +$100  Mar 20
                <p class="m-0"> Olga Nakhodkina +$500 April 4
                <p class="m-0"> James Linahon +$100 April 15
                <p class="m-0"> Manol Manolov +$50 April 17
                <p class="m-0"> Biko Wright +$200 May 27
                <p class="m-0"> Mary Livesey +$100 June 11
                <p class="m-0"> Olga Gleyser +$200 June 24
                <p class="m-0"> Irina Gunica +$50 July 15                
                <p class="m-0"> Anna Derugin +$150 July 20
                <p class="m-0"> Kateryna Sadovnycha +$50 August 6
                <p class="m-0"> Markus Höhnerbach +$50 August 11
                <p class="m-0"> Olga Nakhodkina +$300 January 3
                <p class="m-0"> Jamie Biancalana +$100 January 3
                <p class="m-0"> Serafima Krikunova +$400 January 3
                <p class="m-0"> Sergey Senkin +$50 January 3
                <p class="m-0"> Raleigh Johnson +$200 January 3
                <p class="m-0"> Sravan Gondipalli +$150 January 28
                <p> 
                <p> If you have donated and are missing from the list, or would like to contact me, please reach out to my email dmitryponv@gmail.com
		
	
    </div>
</body>
</html>
