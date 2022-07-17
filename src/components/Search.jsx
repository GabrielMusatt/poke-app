import React from "react";
import { StyledSearchSection } from "./styled";

const PokeSearch = ({ inputValue, setInputValue, handlesPokes }) => {
  return (
    <StyledSearchSection>
      <input
        className="add-new-todo__input"
        type="text"
        value={inputValue}
        onChange={(e) => {
          let event = e;
          handlesPokes(event);
          setInputValue(e.target.value);
        }}
        placeholder="Catch'a'mon"
      />
    </StyledSearchSection>
  );
};

export default PokeSearch;
