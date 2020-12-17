# BowlingGame
Program that calculates the score of a complete game of bowling

addThrow(pins) => adds to the current frame of an ongoing game and returns the current game state in an array format. Returns 'invalid input' if the given pins number is invalid for the game situation. it stops taking input when complete data is available. Need to reset game to start over.

getScore() => Returns the total score only after complete data from 10 frames is available (data is added through addThrow method). If complete data is not available, it returns 'Score not available before the game is over'.

resetGame() => Resets the game so one can start over.
