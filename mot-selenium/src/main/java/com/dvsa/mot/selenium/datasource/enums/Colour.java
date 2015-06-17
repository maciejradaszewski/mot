package com.dvsa.mot.selenium.datasource.enums;

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

    private final String colourName;
    private final String colourId;

    private Colour(String colourName, String colourId) {
        this.colourName = colourName;
        this.colourId = colourId;
    }

    public String getColourName() {
        return colourName;
    }

    public String getColourId() {
        return colourId;
    }

}
