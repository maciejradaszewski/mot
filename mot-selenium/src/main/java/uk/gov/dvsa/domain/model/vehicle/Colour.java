package uk.gov.dvsa.domain.model.vehicle;

public enum Colour {
    NoOtherColour("No other colour", ""),
    Orange("Orange", "E"),
    Black("Black", "P"),
    Cream("Cream", "V"),
    Yellow("Yellow", "F"),
    Green("Green", "H"),
    Gold("Gold", "G"),
    Pink("Pink", "D"),
    Blue("Blue", "J"),
    MultiColour("Multi-Colour", "R"),
    Brown("Brown", "A"),
    Maroon("Maroon", "T"),
    Purple("Purple", "K"),
    Beige("Beige", "S"),
    Red("Red", "C"),
    Silver("Silver", "M"),
    Turquoise("Turquoise", "U"),
    White("White", "N"),
    NotStated("Not Stated", "W"),
    Grey("Grey", "L"),
    Bronze("Bronze", "B");

    private final String name;
    private final String Id;

    private Colour(String colourName, String colourId) {
        this.name = colourName;
        this.Id = colourId;
    }

    public String getName() {
        return name;
    }

    public String getId() {
        return Id;
    }
}
