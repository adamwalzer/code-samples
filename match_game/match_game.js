(function($){
  var cardLetters = ["A","A","B","B","C","C","D","D","E","E","F","F","G","G","H","H"];
  cardLetters = _.shuffle(cardLetters);
  
  var CardModel = Backbone.Model.extend({
    defaults: {
      letter: '',
      backColor: '#0066FF',
      frontColor: '#99CCFF',
      spot: 0
    },
    initialize: function(options) {
        this.backColor = this.defaults.backColor;
        this.frontColor = this.defaults.frontColor;
        this.letter = options.letter ? options.letter : this.defaults.letter;
        this.spot = options.spot ? options.spot : this.defaults.spot;
    }
  });
  
  var cards = [];
  for(i=0; i<cardLetters.length; i++)
  {
	cards.push(new CardModel({letter: cardLetters[i],spot: i}));
  }
  
  var flippedCards = [];
  var BoardView = Backbone.View.extend({
    el: $('.board'), // el attaches to existing element
    initialize: function(){
      _.bindAll(this, 'render'); // every function that uses 'this' as the current object should be in here
      
      this.render();
    },
    events: {
	  "click .back": "flipToFront"
	},
    // `render()` now introduces the card.
    render: function(){
	  for(i=0; i<cards.length; i++)
	  {
	  	var template = _.template( $("#card-back").html(), {card: cards[i]} );
	  	this.$el.append( '<div class="card-container">'+template+'</div>' );
	  }
    },
    flipToFront: function(event) {
      if(flippedCards.length == 2) return false;
      var i = $(event.currentTarget).attr('id');
      var template = _.template( $("#card-front").html(), {card: cards[i]} );
      $(event.currentTarget).removeClass('back').addClass('front');
      $(event.currentTarget).flip({
      	direction: 'lr',
      	color: cards[i].frontColor,
      	content: '<div class="content">'+cards[i].letter+'</div>',
      	speed: 250
      });
      //$(event.currentTarget).parent().html(template);
      flippedCards.push(cards[i]);
      if(flippedCards.length == 2)
		{
      	  setTimeout(function(){
			if(flippedCards[0].letter == flippedCards[1].letter)
			{
				$('#'+flippedCards[0].spot).removeClass('front').css({"background-color": "green"});
				$('#'+flippedCards[1].spot).removeClass('front').css({"background-color": "green"});
			}
			else
			{
			  var id0 = '#'+flippedCards[0].spot;
			  var id1 = '#'+flippedCards[1].spot;
			  $(id0).removeClass('front').addClass('back').revertFlip();
			  $(id1).removeClass('front').addClass('back').revertFlip();
			}
			flippedCards = [];	  
		}, 1000);
	  }
    }
  });

  var boardView = new BoardView();
})(jQuery);