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
  padding: 20px;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
`;

const StyledSearchCard = styled.div`
  padding: 10px;
  box-sizing: border-box;

  .card-content {
    background-color: ${PokeRed};
    height: 100%;
    width: 100%;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;

    .card-description {
      background-color: ${PokeWhite};
      color: ${PokeBlack};
      font-weight: bold;
      text-transform: capitalize;
      padding: 10px;
      border-radius: 10px;
      text-align: center;

      .pokemon-type {
        margin: 0;
        font-size: 80%;
      }
    }
  }
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
  StyledSearchCard,
};
