<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly 
    }
        ?>
<!doctype html>
<html lang="en">

<head>
    <title><?php the_title(); ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
</head>
<?php 

$description = get_post_meta($post->ID, 'description', true); 

$background_color = get_post_meta($post->ID, 'background_color', true); 


?>
<style>


    *,
     ::before,
     ::after {
        box-sizing: inherit;
    }
    *{
        font-family: 'Source Sans Pro', sans-serif;
    }
    .renape-main {
        display: flex;
    }
    
    .page-ren {
        flex: 1 1 0%;
        flex-direction: column;
        -webkit-box-pack: justify;
        justify-content: space-between;
        padding: 24px 12px;
        height: 100%;
        width: 100%;
    }
    
    .styled-background {
        position: fixed;
        inset: 0px;
        z-index: -1;
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
        background-color: <?php echo $background_color; ?>;
    }
    
    .desc-area {
        margin-bottom: 16px;
    }
    
    .headimage {
        -webkit-box-align: center;
        align-items: center;
        flex-direction: column;
        margin-top: 12px;
        margin-bottom: 32px;
        width: 100%;
        height: 100%;
        display: flex;
    }
    
    .ren-image {
        border-radius: 50%;
        width: 96px;
        height: 96px;
        display: block;
        object-fit: contain;
        object-position: initial;
        filter: none;
    }
    
    .title-area {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        -webkit-box-align: center;
        align-items: center;
        margin-left: 12px;
        margin-right: 12px;
        max-width: 100%;
    }
    
    .title-1 {
        grid-column-start: 2;
        margin: 0px;
        color: rgb(255, 255, 255);
        font-size: 16px;
        line-height: 1.5;
        font-weight: 500;
        text-overflow: ellipsis;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
    }
    
    .ren-description {
        padding-left: 40px;
        padding-right: 40px;
    }
    
    .ren-second {
        padding: 0px;
        margin: 0px;
        text-align: center;
        line-height: 1.5;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 500;
    }
    
    .renape-link {
        position: relative;
        z-index: 0;
        overflow: hidden;
        margin-bottom: 16px;
        border: none;
        background-color: rgba(0, 0, 0, 0.6);
        color: rgb(255, 255, 255);
        transition: transform 0.15s cubic-bezier(0, 0.2, 0.5, 3) 0s;
        box-shadow: rgba(10, 11, 13, 0.08) 0px 2px 4px 0px;
    }
    
    .ren-go {
        overflow-wrap: break-word;
        word-break: break-word;
        hyphens: auto;
        white-space: normal;
        background: none;
        color: inherit;
        transition: box-shadow 0.25s cubic-bezier(0.08, 0.59, 0.29, 0.99) 0s, border-color 0.25s cubic-bezier(0.08, 0.59, 0.29, 0.99) 0s, transform 0.25s cubic-bezier(0.08, 0.59, 0.29, 0.99) 0s, background-color 0.25s cubic-bezier(0.08, 0.59, 0.29, 0.99) 0s;
        padding-left: 66px;
        padding-right: 66px;
        margin: 0px;
        border: none;
        font-family: inherit;
        font-weight: inherit;
        font-size: inherit;
        text-align: center;
        cursor: pointer;
        background: none;
        text-decoration: none;
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        height: auto;
        position: relative;
        padding: 16px 20px;
        width: 100%;
        appearance: none;
        box-sizing: border-box;
        vertical-align: middle;
    }
    
    .single-link {
        position: relative;
        hyphens: none;
        padding: 0px;
        margin: 0px;
        line-height: 1.5;
        width: 100%;
        font-weight: 500;
        font-size: 14px;
    }
    
    .renape-link:hover {
        transform: scale(1.02);
    }
    
    .page-ren {
        margin: 0px auto;
        height: 100%;
        width: 100%;
        max-width: 680px;
        padding-bottom: 80px;
    }
</style>

<body>
    <div class="renape-main">
        <div class="page-ren">
            <div class="styled-background"></div>
            <div class="desc-area">
                <div class="headimage">
                    <?php if(get_the_post_thumbnail_url()) :?>
                    <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" class="ren-image">
                </div>
                <?php endif; ?>
                <div class="title-area">
                    <h1 class="title-1"><?php the_title(); ?></h1>
                </div>
                <div class="ren-description">
                    <h2 class="ren-second"><?php echo $description; ?></h2>
                </div>
            </div>
    <?php
        $id = get_the_ID();
        $feture_template = get_post_meta($id, 'single_repeter_group', true);
        if(!empty($feture_template)) {
	?>
        <?php  foreach ($feture_template as $item) { ?>
                <div class="renape-link">
                    <a href="<?php echo $item['tdesc']; ?>" class="ren-go">
                        <p class="single-link"><?php echo $item['title']; ?></p>
                    </a>
                </div>
        <?php } ?>
    <?php } ?>
        </div>
    </div>
</body>

</html>


