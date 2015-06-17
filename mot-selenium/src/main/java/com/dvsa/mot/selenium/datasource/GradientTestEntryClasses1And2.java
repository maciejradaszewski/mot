package com.dvsa.mot.selenium.datasource;

public class GradientTestEntryClasses1And2 {

    public static final GradientTestEntryClasses1And2 GRADIENT_ABOVE_ABOVE =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Above),
                    new GradientTest(GradientEfficiency.Above));
    public static final GradientTestEntryClasses1And2 GRADIENT_ABOVE_BETWEEN =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Above),
                    new GradientTest(GradientEfficiency.Between));
    public static final GradientTestEntryClasses1And2 GRADIENT_ABOVE_BELOW =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Above),
                    new GradientTest(GradientEfficiency.Below));
    public static final GradientTestEntryClasses1And2 GRADIENT_BETWEEN_ABOVE =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Between),
                    new GradientTest(GradientEfficiency.Above));
    public static final GradientTestEntryClasses1And2 GRADIENT_BETWEEN_BETWEEN =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Between),
                    new GradientTest(GradientEfficiency.Between));
    public static final GradientTestEntryClasses1And2 GRADIENT_BETWEEN_BELOW =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Between),
                    new GradientTest(GradientEfficiency.Below));
    public static final GradientTestEntryClasses1And2 GRADIENT_BELOW_ABOVE =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Below),
                    new GradientTest(GradientEfficiency.Above));
    public static final GradientTestEntryClasses1And2 GRADIENT_BELOW_BETWEEN =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Below),
                    new GradientTest(GradientEfficiency.Between));
    public static final GradientTestEntryClasses1And2 GRADIENT_BELOW_BELOW =
            new GradientTestEntryClasses1And2(new GradientTest(GradientEfficiency.Below),
                    new GradientTest(GradientEfficiency.Below));


    public enum GradientEfficiency {Above, Between, Below}


    public final GradientTest control1;
    public final GradientTest control2;


    public static class GradientTest {
        public GradientEfficiency efficiency;

        public GradientTest(GradientEfficiency efficiency) {
            super();
            this.efficiency = efficiency;
        }
    }

    public GradientTestEntryClasses1And2(GradientTest control1, GradientTest control2) {
        super();
        this.control1 = control1;
        this.control2 = control2;
    }

}
