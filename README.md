# The Game
N amount of points compete for the land. It is the war-free world therefore competition is more of a fare trade, not of an advantage overtake. Is it possible to have such model of the world at all?

## Run

`composer install`  
`php simulate.php`

The output that works initially:  

```output-initial

--------------------------------------------------------------------------------
   A: 6.16   A: 8.22   A: 4.10   A: 7.19   B: 8.23         .         .         .
    A: 3.7    A: 1.1    A: 2.4   A: 5.13   B: 6.17         .         .         .
    B: 3.8    B: 1.2    B: 2.5   B: 4.11   B: 5.14   B: 7.20   C: 8.24         .
    C: 1.3    C: 2.6    C: 3.9   C: 4.12   C: 5.15   C: 6.18   C: 7.21         .
--------------------------------------------------------------------------------

```

The issue of players attaching to the birthplace.  
Player A is blocked, no player can make a move by interacting with the birthplace of other player first.  

```output-birthplace-issue

--------------------------------------------------------------------------------
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
    I: 1.9    B: 1.2    F: 1.6         .         .         .         .         .
    E: 1.5    A: 1.1    C: 1.3         .         .         .         .         .
    H: 1.8    D: 1.4    G: 1.7         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
         .         .         .         .         .         .         .         .
--------------------------------------------------------------------------------



```


Also, the performance is slow.  
The goal is to make The World of 3.600.000.000 (x) x 1.800.000.000 (y) in size (6.480.000.000.000.000.000 pixels in total) with 10.000.000.000 players playing one game in at least 24 hours on my laptop.  

Quite a lot to improve...
