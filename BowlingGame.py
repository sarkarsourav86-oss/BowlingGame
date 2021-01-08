class BowlingGame:
    _gameRecords = {}
    _throws = []
    _possible_pins = [0,1,2,3,4,5,6,7,8,9,10]
    _possible_pins_next_throw = [0,1,2,3,4,5,6,7,8,9,10]
    _current_frame = 1
    _bonus_throws = False
    _frame_score = []
    
    def resetGame(self):
        self._gameRecords = []
        self._current_frame = 1
        self._throws = []
        self._possible_pins_next_throw = self._possible_pins
        print("Game has been reset")
        
    def getScore(self):
        current_frame = 1
        throw_index = 0
        while current_frame <= 10 and (len(self._throws) > throw_index + 1):
            if self._throws[throw_index] + self._throws[throw_index + 1] < 10:  #// if an open frame, go to next frame and increase throw index by 2
                self._frame_score.append( self._throws[throw_index] + self._throws[throw_index + 1])
                throw_index = throw_index + 2
                current_frame = current_frame + 1
                continue
            
            if len(self._throws) <= throw_index + 2: #//if next two throws are not available, break the loop
            
                break
            

            self._frame_score.append(self._throws[throw_index] + self._throws[throw_index + 1] + self._throws[throw_index + 2]) #//count the score for a spare or a strike
            
            if self._throws[throw_index] == 10: #//increase throw index by 1 for a strike

                throw_index = throw_index + 1
                
            elif self._throws[throw_index] + self._throws[throw_index + 1] == 10: # //increase throw index by 2 for a spare

                throw_index += 2

            current_frame = current_frame + 1
            
        score = 0

        if len(self._frame_score) != 10:
        
            print( 'Score not available before the game is over')
            return
        
        else:
            for val in self._frame_score:
                score = score +  val

        
        
        return score
        
        
        
        
        
        
    def addThrow(self,pins):
        if self._current_frame > 10:
            print('Game over')
            return
        if(pins in self._possible_pins_next_throw):                        #valid input from user
            if self._current_frame < 10:                                    #open frame
                if pins < 10:
                    if self._gameRecords.get(self._current_frame) is not None:
                        self._gameRecords[self._current_frame].append(pins)
                    else:
                        self._gameRecords[self._current_frame] = [pins]
                    
                    if self._gameRecords[self._current_frame] is not None and len(self._gameRecords[self._current_frame]) == 2:   #both throws taken
                        
                        self._current_frame = self._current_frame + 1
                        self._possible_pins_next_throw = self._possible_pins
                    else:   #one throw taken
                        self._possible_pins_next_throw = self._possible_pins[0: (10-pins+1)]
    
                else: #strike or spare
                     self._gameRecords[self._current_frame] = [pins]
                     self._current_frame = self._current_frame + 1
                     self._possible_pins_next_throw = self._possible_pins
            elif self._current_frame == 10:
                
                if self._gameRecords.get(self._current_frame) is None and pins == 10:    #//first throw 10th frame and strike
                    
                    self.bonus_throws = True
                    self._possible_pins_next_throw = self._possible_pins
                
                elif self._gameRecords.get(self._current_frame) is None and pins < 10:  #//first throw 10th frame and open
                    
                    self.bonus_throws = False
                    self_possible_pins_next_throw = self._possible_pins[0: (10-pins+1)]
                
                elif len(self._gameRecords[self._current_frame]) == 1 and (self._gameRecords[self._current_frame][0] + pins == 10): #second throw 10th frame and spare
                    
                    self.bonus_throws = True
                    self._possible_pins_next_throw = self._possible_pins
                
                elif len(self._gameRecords[self._current_frame]) == 1 and (self._gameRecords[self._current_frame][0] + pins < 10): #second throw 10th frame and open
                    
                    self.bonus_throws = False
                    self._possible_pins_next_throw = []
                
                
                if self._gameRecords.get(self._current_frame) is not None:
                    self._gameRecords[self._current_frame].append(pins)
                
                else:
                    self._gameRecords[self._current_frame] = [pins]
                
                if self.bonus_throws and len(self._gameRecords[self._current_frame]) == 3: #when bonus, 3 available throws 
                    self._current_frame = self._current_frame + 1
                
                elif not self.bonus_throws and len(self._gameRecords[self._current_frame]) == 2:  #//when no bonus, 2 available throws 
                    self._current_frame = self._current_frame + 1
            self._throws.append(pins)
        else:
            print('invalid pins')
            return
        

