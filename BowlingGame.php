<?php

namespace App\Classes;

class BowlingGame {
    private $throws = [];
    private $frame_score = [];
    private $addThrowIndex = 0;
    private $currentAddThrowFrame = 1;
    private $frameWiseThrowBreakDown = [];
    private const POSSIBLE_PINS = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    private $possible_next_throw_pins_down = BowlingGame::POSSIBLE_PINS;
    private $bonus_throws = false;

    public function addThrow($pins_knocked)
    {
        if($this->currentAddThrowFrame > 10) //game over
        {
            echo 'Please reset to start another game';
            return;
        }
        if(in_array($pins_knocked, $this->possible_next_throw_pins_down)) //valid input from user
        {
            if($this->currentAddThrowFrame == 10) //10th frame is going on
            {
                if(!isset($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame]) && $pins_knocked == 10) //first throw 10th frame and strike
                {
                    $this->bonus_throws = true;
                    $this->possible_next_throw_pins_down = BowlingGame::POSSIBLE_PINS;
                }
                else if(!isset($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame]) && $pins_knocked < 10) //first throw 10th frame and open
                {
                    $this->bonus_throws = false;
                    $this->possible_next_throw_pins_down = array_slice(BowlingGame::POSSIBLE_PINS, 0, (10 - $pins_knocked + 1));
                }
                else if(count($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame]) == 1 && ($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame][0] + $pins_knocked == 10)) //second throw 10th frame and spare
                {
                    $this->bonus_throws = true;
                    $this->possible_next_throw_pins_down = BowlingGame::POSSIBLE_PINS;
                }
                else if(count($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame]) == 1 && ($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame][0] + $pins_knocked < 10)) //second throw 10th frame and open
                {
                    $this->bonus_throws = false;
                    $this->possible_next_throw_pins_down = [];
                }
                $this->frameWiseThrowBreakDown[$this->currentAddThrowFrame][] = $pins_knocked;
                if($this->bonus_throws && count($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame]) == 3) //when bonus, 3 available throws 
                {
                    $this->currentAddThrowFrame++;
                }
                else if(!$this->bonus_throws && count($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame]) == 2) //when no bonus, 2 available throws 
                {
                    $this->currentAddThrowFrame++;
                }
            }
            else //other than 10th frame
            {
                if($pins_knocked < 10) //open frame
                {
                    $this->frameWiseThrowBreakDown[$this->currentAddThrowFrame][] = $pins_knocked;
                    if(count($this->frameWiseThrowBreakDown[$this->currentAddThrowFrame]) == 2) //both throws taken
                    {
                        $this->currentAddThrowFrame++;
                        $this->possible_next_throw_pins_down = BowlingGame::POSSIBLE_PINS;
                    }
                    else //one taken
                    {
                        $this->possible_next_throw_pins_down = array_slice(BowlingGame::POSSIBLE_PINS, 0, (10 - $pins_knocked + 1));
                    }
                }
                else if($pins_knocked  = 10) //strike or spare
                {
                    $this->frameWiseThrowBreakDown[$this->currentAddThrowFrame++][] = $pins_knocked;
                    $this->possible_next_throw_pins_down = BowlingGame::POSSIBLE_PINS;
                }
            }
            $this->throws[] = $pins_knocked;
            return $this->frameWiseThrowBreakDown;
        }
        else
        {
            echo 'invalid input';
            return;
        }
    }
    public function getScore()
    {
        $current_frame = 1;
        $throw_index = 0;
        while($current_frame <= 10 && isset($this->throws[$throw_index + 1]))
        {
            if($this->throws[$throw_index] + $this->throws[$throw_index + 1] < 10) // if an open frame, go to next frame and increase throw index by 2
            {
                
                $this->frame_score[$current_frame] = $this->throws[$throw_index] + $this->throws[$throw_index + 1];
                $throw_index += 2;
                $current_frame++;
                continue;
            }
            if(!isset($this->throws[$throw_index + 2])) //if next two throws are not available, break the loop
            {
                break;
            }

            $this->frame_score[$current_frame] = $this->throws[$throw_index] + $this->throws[$throw_index + 1] + $this->throws[$throw_index + 2]; //count the score for a spare or a strike
            
            if($this->throws[$throw_index] == 10) //increase throw index by 1 for a strike
            {
                $throw_index += 1;
            }
            else if($this->throws[$throw_index] + $this->throws[$throw_index + 1] == 10) //increase throw index by 2 for a spare
            {
                $throw_index += 2;
            }
            $current_frame++;
            
        }
        $score = 0;

        if(count($this->frame_score) != 10)
        {
            return 'Score not available before the game is over';
        }
        else
        {
            foreach($this->frame_score as $val)
            {
                $score += $val;
            }
        }
        
        
        return $score;
    }

    public function resetGame()
    {
        $this->frameWiseThrowBreakDown = [];
        $this->currentAddThrowFrame = 1;
        $this->throws = [];
        $this->possible_next_throw_pins_down = BowlingGame::POSSIBLE_PINS;
        echo 'Game has been reset';
    }
    

}