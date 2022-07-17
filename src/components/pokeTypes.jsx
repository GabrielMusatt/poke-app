const pokeType = (x) => {
  let type;
  switch (x) {
    case "normal":
      type = <p style={{ color: "olive" }}>{x}</p>;
      break;
    case "fire":
      type = <p style={{ color: "orange" }}>{x}</p>;
      break;
    case "water":
      type = <p style={{ color: "blue" }}>{x}</p>;
      break;
    case "grass":
      type = <p style={{ color: "green" }}>{x}</p>;
      break;
    case "electric":
      type = <p style={{ color: "yellow" }}>{x}</p>;
      break;
    case "ice":
      type = <p style={{ color: "aqua" }}>{x}</p>;
      break;
    case "fighting":
      type = <p style={{ color: "red" }}>{x}</p>;
      break;
    case "poison":
      type = <p style={{ color: "purple" }}>{x}</p>;
      break;
    case "ground":
      type = <p style={{ color: "antiquewhite" }}>{x}</p>;
      break;
    case "flying":
      type = <p style={{ color: "mediumslateblue" }}>{x}</p>;
      break;
    case "psychic":
      type = <p style={{ color: "magenta" }}>{x}</p>;
      break;
    case "bug":
      type = <p style={{ color: "greenyellow" }}>{x}</p>;
      break;
    case "rock":
      type = <p style={{ color: "goldenrod" }}>{x}</p>;
      break;
    case "ghost":
      type = <p style={{ color: "indigo" }}>{x}</p>;
      break;
    case "dark":
      type = <p style={{ color: "brown" }}>{x}</p>;
      break;
    case "dragon":
      type = <p style={{ color: "navy" }}>{x}</p>;
      break;
    case "steel":
      type = <p style={{ color: "gray" }}>{x}</p>;
      break;
    case "fairy":
      type = <p style={{ color: "pink" }}>{x}</p>;
      break;

    default:
      type = <p style={{ color: "black" }}>{x}</p>;
      break;
  }
  return type;
};

export default pokeType;
