# The Game
N amount of points compete for the land. It is the war-free world therefore competition is more of a fare trade, not of an advantage overtake. Is it possible to have such model of the world at all?

## Run

`docker-compose up --build -d && docker-compose exec fpm composer install`  
`docker-compose exec fpm php simulate.php`  
`docker-compose down`

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

To overcome the latest issue, compensation system (think - money, other benefits, etc.) has been introduced.  
The player who cannot move anymore gets promoted by the 1 unit of compensation for each event of limitation.  
The end result is - each player is considered to become equally happy by getting territory or compensation.  
  
Here is how this looks like in action:  

```output-compensation
--------------------------------------------------------------------------------
I:5.45    B:5.38    B:6.47    B:7.56    B:8.65    B:9.74    B:10.83   B:12.101  
I:4.36    B:4.29    F:5.42    F:7.60    F:9.78    B:11.92   F:12.105  B:13.110  
I:3.27    B:3.20    F:3.24    F:6.51    F:8.69    F:10.87   F:11.96   F:13.114  
I:2.18    B:2.11    F:2.15    F:4.33    C:7.57    C:10.84   C:12.102  C:14.120  
I:1.9     B:1.2     F:1.6     C:3.21    C:5.39    C:8.66    C:11.93   C:13.111  
E:1.5     A:1.1     C:1.3     C:2.12    C:4.30    C:6.48    C:9.75    G:11.97   
H:1.8     D:1.4     G:1.7     G:2.16    G:3.25    G:5.43    G:7.61    G:9.79    
H:2.17    D:2.13    D:3.22    G:4.34    G:6.52    G:8.70    G:10.88   G:12.106  
H:3.26    D:4.31    D:5.40    D:6.49    D:8.67    D:10.85   D:12.103  G:13.115  
H:4.35    H:5.44    D:7.58    D:9.76    D:11.94   D:13.112  D:14.121  G:14.124  
H:6.53    H:7.62    H:9.80    H:11.98   H:13.116  D:15.130  D:16.139  G:15.133  
H:8.71    H:10.89   H:12.107  H:14.125  H:15.134  H:16.143  D:17.148  G:16.142  
--------------------------------------------------------------------------------


Player : Compensation
--------------------------------------------------------------------------------
  A : 16  
  B : 4   
  C : 3   
  D : 0   
  E : 16  
  F : 4   
  G : 1   
  H : 1   
  I : 12  
--------------------------------------------------------------------------------
In total (aka. GDP?): 57
--------------------------------------------------------------------------------

```

The question is:  
What should we do with the compensation now?    

Also, the performance is slow.  
The goal is to make The World of 3.600.000.000 (x) x 1.800.000.000 (y) in size (6.480.000.000.000.000.000 pixels in total) with 10.000.000.000 players playing one game in at least 24 hours on my laptop.  

Quite a lot to improve...
