package uk.gov.dvsa.domain.model.vehicle;

public enum CountryOfRegistration {
    Great_Britain("GB, UK, ENG, CYM, SCO (UK) - Great Britain", "1", "GB"),
    Northern_Ireland("GB, NI (UK) - Northern Ireland", "2", "NI"),
    Alderney("GBA (GG) - Alderney", "3", "GBA"),
    Guernsey("GBG (GG) - Guernsey", "4", "GBG"),
    Jersey("GBJ (JE) - Jersey", "5", "GBJ"),
    IsleOfMan("GBM (IM) - Isle of Man", "6", "GBM"),
    Austria("A (AT) - Austria", "7", "AT"),
    Belgium("B (BE) - Belgium", "8", "BE"),
    Bulgaria("BG (BG) - Bulgaria", "9", "BG"),
    Cyprus("CY (CY) - Cyprus", "10", "CY"),
    Czech_Republic("CZ (CZ) - Czech Republic", "11", "CZ"),
    Denmark("DK (DK) - Denmark", "12", "DK"),
    Estonia("EST (EE) - Estonia", "13", "EE"),
    Finland("FIN (FI) - Finland", "14", "FI"),
    France("F (FR) - France", "15", "FR"),
    Germany("D (DE) - Germany", "16", "DE"),
    Gibraltar("GBZ (GI) - Gibraltar", "17", "GI"),
    Greece("GR (GR) - Greece", "18", "GR"),
    Hungary("H (HU) - Hungary", "19", "HU"),
    Ireland("IRL (IE) - Ireland", "20", "IE"),
    Italy("I (IT) - Italy", "21", "IT"),
    Latvia("LV (LV) - Latvia", "22", "LV"),
    Lithuania("LT (LT) - Lithuania", "23", "LT"),
    Luxembourg("L (LU) - Luxembourg", "24", "LU"),
    Malta("M (MT) - Malta", "25", "MT"),
    Netherlands("NL (NL) - Netherlands", "26", "NL"),
    Poland("PL (PL) - Poland", "27", "PL"),
    Portugal("P (PT) - Portugal", "28", "PT"),
    Romania("RO (RO) - Romania", "29", "RO"),
    Slovakia("SK (SK) - Slovakia", "30", "SK"),
    Slovenia("SLO (SI) - Slovenia", "31", "SI"),
    Spain("E (ES) - Spain", "32", "ES"),
    Sweden("S (SE) - Sweden", "33", "SE"),
    Non_EU("Non EU", "34", "XNEU"),
    Not_Known("Not Known", "35", "XUKN"),
    Not_Applicable("Not Applicable", "36", "XNA"),
    Please_Select("Please select","","");

    private final String country;
    private final String registrationId;
    private final String registrationCode;

    private CountryOfRegistration(String country, String registrationId, String registrationCode) {
        this.country = country;
        this.registrationId = registrationId;
        this.registrationCode = registrationCode;
    }

    public String getCountry() {
        return country;
    }

    public String getRegistrationId() {
        return registrationId;
    }

    public String getRegistrationCode() {
        return registrationCode;
    }

    public static CountryOfRegistration getRandomCountry(){
        return values()[(int) (Math.random() * values().length)];
    }
}

