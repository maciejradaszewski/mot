package com.dvsa.mot.selenium.datasource.enums;

public enum SiteAssessment {
    Green("Green", "Risk Status: Green", 324.1),
    Amber("Amber", "Risk Status: Amber", 459.2),
    Red("Red", "Risk Status: Red", 459.3);

    private final String riskStatusColour;
    private final String riskStatusText;
    private final double riskStatusUpperLimit;

    private SiteAssessment(String riskStatusColour, String riskStatusText,
            double riskStatusUpperLimit) {
        this.riskStatusColour = riskStatusColour;
        this.riskStatusText = riskStatusText;
        this.riskStatusUpperLimit = riskStatusUpperLimit;
    }

    public String getRiskStatusColour() {
        return this.riskStatusColour;
    }

    public String getRiskStatusText() {
        return this.riskStatusText;
    }

    public double getRiskStatusUpperLimit() {
        return this.riskStatusUpperLimit;
    }
}
