import styled from "styled-components";
import { PokeRed, PokeWhite, PokeBlack } from "../constants/colors";

const commonStyles = `
  padding: 20px 30px;
`;

const StyledPokedex = styled.div`
  background-color: ${PokeWhite};
  max-width: 1100px;
  margin: 60px auto;
`;

const StyledSearchSection = styled.div`
  background-color: ${PokeRed};
  color: ${PokeWhite};
  ${commonStyles}

  input {
    border: 1px solid ${PokeWhite};
    background-color: ${PokeRed};
    color: ${PokeBlack};
    padding: 10px 20px 10px 10px;
    border-radius: 8px;

    &::placeholder {
      color: ${PokeWhite};
    }

    &:focus-visible {
      border: none;
    }
  }
`;

const StyledResultSection = styled.div`
  background-color: ${PokeBlack};
  color: ${PokeWhite};
  ${commonStyles}
`;

const StyledOwnedSection = styled.div`
  background-color: ${PokeWhite};
  color: ${PokeBlack};
  ${commonStyles}
`;

export {
  StyledPokedex,
  StyledSearchSection,
  StyledResultSection,
  StyledOwnedSection,
};
