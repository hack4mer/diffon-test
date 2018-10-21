# Diffon
A PHP library to find the differences between two given directories

Gives following information about the directories: 
- Files/Directories that exist in the first directory only.
- Files/Directories that exist in second directory only.
- Files/Directories that exist in both the directories.
- Files that exist in both the directories, but contain different data.

But, what about directories that exist in both the directories and contain different things?
For that, we should implement Diffon with recursion in our code.
### Installation
    composer require hack4mer/diffon

### Usage
Let's say you have two directories with following content:
    
    dir1 => hack4mer.txt, github.txt, winter.txt
    dir2 => hack4mer.txt (with different content), github.txt, summer.txt
    
To compare the directories with Diffon:
    
    <?php
    //include composer autoloader
    include 'vendor/autoload.php'
    use Hack4mer\Diffon\Dinffon;
    
    $diffon = new Diffon();
    $diffon->setSource("dir1")->setDestination("dir2");
    $difference = $diffon->diff();
    
    print_r($difference);
    ?>
    
Output:

    Array
    (
        [only_in_source] => Array
            (
                [2] => winter.txt
            )
    
        [only_in_destination] => Array
            (
                [2] => summer.txt
            )
    
        [in_both] => Array
            (
                [0] => github.txt
                [1] => hack4mer.txt
            )
    
        [not_same] => Array
            (
                [1] => hack4mer.txt
            )
    
    )
    
Notice the index of the array elements, they give information about the position of each file in given directories if sorted alphabetically.