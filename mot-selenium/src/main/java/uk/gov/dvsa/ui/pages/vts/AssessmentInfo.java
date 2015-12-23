package uk.gov.dvsa.ui.pages.vts;

import uk.gov.dvsa.domain.model.User;

import java.util.Map;

public class AssessmentInfo {

    private String score;
    private String color;
    private Map<String, User> assessmentActors;

    public AssessmentInfo(String score, String color, Map<String, User> assessmentActors) {
        this.score = score;
        this.color = color;
        this.assessmentActors = assessmentActors;
    }

    public String getAeRepUserId() {
        return assessmentActors.get("areaOffice1User").getUsername();
    }

    public String getAeRepRole() {
        return "vehicleExaminer";
    }

    public String getAeRepFullName() {
        return assessmentActors.get("vehicleExaminer").getFullName();
    }

    public String getTesterUserId() {
        return assessmentActors.get("tester").getUsername();
    }

    public String getScore() {
        return score;
    }

    public String getColor() {
        return color;
    }

    public String getColourBadgeType(){
        return getTypeByColour(color);
    }

    private String getTypeByColour(String color) {
        switch (color) {
            case "Green":
                return "success";
            case "Red":
                return "alert";
            case "Amber":
                return "warn";
            default:
                return "Invalid Color";
        }
    }
}
