package uk.gov.dvsa.domain.api.request;


import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;
import uk.gov.dvsa.domain.model.User;

@JsonInclude(JsonInclude.Include.NON_NULL)
@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
public class Requestor{
    protected String username;
    protected String password;

    protected Requestor(User user){
        username = user.getUsername();
        password = user.getPassword();
    }
}
